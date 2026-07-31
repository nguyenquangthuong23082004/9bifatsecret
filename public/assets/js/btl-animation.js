/* =========================================================
   BITIEL Landing – GSAP Scroll Animation System
   -----------------------------------------------------------------
   TƯ DUY: animation KHÔNG "được kích hoạt rồi tự chạy".
   Nó bị BUỘC vào vị trí thanh cuộn (scrub).

     tiến độ animation = f(scrollY)

   Hệ quả:
   • Cuộn lên là animation chạy lùi chính xác từng frame → lặp lại vô hạn,
     không cần restart()/pause() hay cờ trạng thái nào.
   • Không thể có bug "kẹt ở opacity:0", vì trạng thái luôn được suy ra từ
     vị trí cuộn hiện tại chứ không phải từ một sự kiện đã lỡ mất.
   • Cảm giác mượt đến từ `scrub: <số giây>` — animation đuổi theo con lăn
     với một quán tính, chứ không phải từ easing.

   CẤU TRÚC
     Phần A – Hạ tầng: log chẩn đoán + token chuyển động + hàm reveal()
     Phần B – Khai báo từng section
     Phần C – Khởi động, breakpoint, giữ toạ độ trigger luôn đúng
   ========================================================= */
(function () {
  'use strict';

  /* ============================================================
     PHẦN A00 – ÉP VỀ ĐẦU TRANG KHI F5
     Mặc định trình duyệt tự khôi phục vị trí cuộn cũ sau khi F5, NHƯNG nó làm
     việc đó trước khi ảnh tải xong, tức là ở một layout hoàn toàn khác. Kết
     quả: người dùng bị thả xuống giữa một section với animation dở dang, và
     ScrollTrigger thì tính toạ độ trên chiều cao trang chưa đúng.
     Chặn từ gốc: tự quản lý vị trí cuộn, luôn bắt đầu từ 0.
     Đoạn này CỐ Ý nằm ngoài mọi hàm để chạy ngay khi file được nạp.
     ============================================================ */

  // Có hash (#contact, #faq...) thì người dùng đang chủ động tới một mục cụ
  // thể — tôn trọng điều đó, không kéo về đầu.
  var HAS_HASH = !!(location.hash && location.hash.length > 1);

  if (!HAS_HASH && 'scrollRestoration' in history) {
    history.scrollRestoration = 'manual';
    window.scrollTo(0, 0);
  }

  /** Ghim về đầu trang. Gọi lại vài lần vì trình duyệt có thể khôi phục muộn. */
  function forceTop() {
    if (HAS_HASH) return;
    window.scrollTo(0, 0);
  }

  /* ============================================================
     PHẦN A0 – LOG CHẨN ĐOÁN
     Bật/tắt: đổi DEBUG bên dưới, hoặc thêm ?animdebug=0 vào URL.
     ============================================================ */

  var DEBUG = true;

  try {
    if (location.search.indexOf('animdebug=0') !== -1) DEBUG = false;
    if (location.search.indexOf('animdebug=1') !== -1) DEBUG = true;
  } catch (e) {}

  var TAG = '%c[BTL]';
  var CSS_OK = 'color:#0a0;font-weight:bold';
  var CSS_BAD = 'color:#c00;font-weight:bold';
  var CSS_INFO = 'color:#06c;font-weight:bold';

  var report = [];   // gom kết quả từng block để in bảng tổng kết

  function logInfo() {
    if (!DEBUG) return;
    var args = Array.prototype.slice.call(arguments);
    console.log.apply(console, [TAG + ' ' + args.shift(), CSS_INFO].concat(args));
  }

  function logOk(name, detail) {
    if (!DEBUG) return;
    console.log(TAG + ' %c✓ ' + name, CSS_INFO, CSS_OK, detail || '');
  }

  function logBad(name, reason) {
    if (!DEBUG) return;
    console.log(TAG + ' %c✗ ' + name + '  → ' + reason, CSS_INFO, CSS_BAD);
  }

  /** Mô tả ngắn gọn một phần tử DOM để in ra console. */
  function describe(el) {
    if (!el) return 'null';
    var s = el.tagName ? el.tagName.toLowerCase() : '?';
    if (el.className && typeof el.className === 'string') {
      s += '.' + el.className.trim().split(/\s+/).slice(0, 3).join('.');
    }
    return s;
  }

  function count(el) {
    if (!el) return 0;
    return Array.isArray(el) ? el.length : 1;
  }

  /* ============================================================
     PHẦN A – HẠ TẦNG
     ============================================================ */

  /**
   * Token chuyển động. Đổi ở đây là đổi cảm giác của cả trang.
   *
   *  start / end : quãng cuộn mà animation diễn ra. 'top 90%' → 'top 40%'
   *                nghĩa là: bắt đầu khi đỉnh phần tử chạm mốc 90% chiều cao
   *                màn hình, kết thúc khi nó lên tới mốc 40%. Tức đúng 50vh
   *                cuộn cho mọi khối, bất kể khối đó cao hay thấp.
   *  scrub       : độ trễ (giây) khi animation đuổi theo con lăn.
   *                0 = dính chặt, 1 = mượt, 2+ = lười biếng/điện ảnh.
   *  scale       : hệ số nhân cho mọi khoảng cách px (mobile đi ít hơn).
   */
  var MOTION = {
    desktop: { start: 'top 90%', end: 'top 40%', scrub: 1,   scale: 1 },
    mobile:  { start: 'top 95%', end: 'top 45%', scrub: 0.8, scale: 0.55 }
  };

  var M = MOTION.desktop;   // token đang áp dụng, do matchMedia gán

  /** Quy đổi khoảng cách px theo breakpoint. */
  function d(px) {
    return Math.round(px * M.scale);
  }

  // --- Tiện ích DOM (luôn an toàn với null) ---
  function q(root, sel) { return root ? root.querySelector(sel) : null; }
  function qq(root, sel) { return root ? gsap.utils.toArray(root.querySelectorAll(sel)) : []; }

  function pick(root, selectors) {
    if (!root) return [];
    return selectors.map(function (s) { return root.querySelector(s); }).filter(Boolean);
  }

  function firstOf() {
    for (var i = 0; i < arguments.length; i++) {
      var el = arguments[i];
      if (Array.isArray(el)) { if (el.length) return el[0]; continue; }
      if (el) return el;
    }
    return null;
  }

  function isEmpty(el) {
    return !el || (Array.isArray(el) && el.length === 0);
  }

  /**
   * reveal() – Buộc một timeline vào quãng cuộn của phần tử trigger.
   *
   * @param {string}   config.name     Tên block, chỉ dùng cho log.
   * @param {Element}  config.trigger  Phần tử làm mốc cuộn (bắt buộc).
   * @param {string}   [config.start]  Mặc định M.start.
   * @param {string}   [config.end]    Mặc định M.end.
   * @param {number}   [config.scrub]  Mặc định M.scrub.
   * @param {Array}    config.steps    [{ el, from, to, at }]
   *        `duration` trong `to` KHÔNG còn là giây — nó là TRỌNG SỐ tương
   *        đối, vì cả timeline bị kéo giãn cho vừa quãng cuộn start→end.
   * @param {Function} [config.loop]   Trả về mảng tween lặp vô hạn. Tween lặp
   *        phải dùng thuộc tính KHÁC tween reveal (yPercent vs y).
   */
  function reveal(config) {
    var name = config.name || '(chưa đặt tên)';

    if (!config.trigger) {
      logBad(name, 'KHÔNG TÌM THẤY TRIGGER');
      report.push({ block: name, status: 'no trigger', els: 0 });
      return null;
    }

    var all = config.steps || [];
    var steps = all.filter(function (s) { return s && !isEmpty(s.el); });

    if (steps.length === 0) {
      logBad(name, 'KHÔNG CÓ PHẦN TỬ NÀO (trigger=' + describe(config.trigger) + ')');
      report.push({ block: name, status: 'no elements', els: 0 });
      return null;
    }

    var total = 0;
    steps.forEach(function (s) { total += count(s.el); });

    if (steps.length < all.length) {
      logInfo('%s: bỏ qua %d/%d bước vì thiếu phần tử',
        name, all.length - steps.length, all.length);
    }

    var loopTweens = [];

    function killLoops() {
      loopTweens.forEach(function (tw) { tw.kill(); });
      loopTweens = [];
    }

    function startLoops() {
      killLoops();
      if (config.loop) loopTweens = config.loop() || [];
    }

    var st = null;

    var tl = gsap.timeline({
      scrollTrigger: {
        trigger: config.trigger,
        start: config.start || M.start,
        end: config.end || M.end,
        scrub: config.scrub !== undefined ? config.scrub : M.scrub,
        // Tính lại giá trị start của mọi tween mỗi lần refresh — cần thiết vì
        // khoảng cách px phụ thuộc breakpoint và layout còn dịch khi ảnh load.
        invalidateOnRefresh: true,
        onEnter: DEBUG ? function () { logInfo('▶ %s vào tầm nhìn', name); } : null,
        onLeaveBack: DEBUG ? function () { logInfo('◀ %s ra khỏi tầm nhìn', name); } : null
      },
      onComplete: startLoops,
      onReverseComplete: killLoops
    });

    steps.forEach(function (step) {
      if (step.at === undefined) {
        tl.fromTo(step.el, step.from, step.to);
      } else {
        tl.fromTo(step.el, step.from, step.to, step.at);
      }
    });

    st = tl.scrollTrigger;
    logOk(name, 'trigger=' + describe(config.trigger) + '  phần tử=' + total);
    report.push({ block: name, status: 'ok', els: total, st: st, trigger: config.trigger });

    return tl;
  }

  /** Rút gọn cho khối chỉ có 1 nhóm phần tử. */
  function revealGroup(name, trigger, el, from, to, opts) {
    return reveal({
      name: name,
      trigger: trigger,
      start: opts && opts.start,
      end: opts && opts.end,
      scrub: opts && opts.scrub,
      steps: [{ el: el, from: from, to: to }]
    });
  }

  /* --- Ba từ vựng chuyển động dùng chung cả trang --------------------
     Tiêu đề đi XUỐNG, nội dung đi LÊN, điểm nhấn thì BUNG ra.           */

  /** Trượt từ trên xuống – dùng cho mọi cụm tiêu đề. */
  function revealDown(name, trigger, els, opts) {
    opts = opts || {};
    var dist = opts.dist === undefined ? 45 : opts.dist;
    return revealGroup(name, trigger, els,
      { autoAlpha: 0, y: -d(dist) },
      {
        autoAlpha: 1, y: 0, ease: 'none',
        duration: opts.duration || 1,
        stagger: opts.stagger === undefined ? 0.25 : opts.stagger
      },
      opts);
  }

  /** Trượt từ dưới lên – dùng cho nội dung, ảnh, thẻ. */
  function revealUp(name, trigger, els, opts) {
    opts = opts || {};
    var dist = opts.dist === undefined ? 40 : opts.dist;
    return revealGroup(name, trigger, els,
      { autoAlpha: 0, y: d(dist) },
      {
        autoAlpha: 1, y: 0, ease: 'none',
        duration: opts.duration || 1,
        stagger: opts.stagger === undefined ? 0.2 : opts.stagger
      },
      opts);
  }

  var HEAD_2LINE = ['.sec-head__label', '.sec-head__title .line1', '.sec-head__title .line2'];
  var HEAD_PLAIN = ['.sec-head__label', '.sec-head__title'];
  var EASE_POP = 'back.out(1.7)';

  /** Tìm section theo class chính, có selector dự phòng. Log nếu không thấy. */
  function section(name, sel, alt) {
    var el = document.querySelector(sel) || (alt ? document.querySelector(alt) : null);
    if (!el) {
      logBad(name, 'KHÔNG TÌM THẤY SECTION (' + sel + (alt ? ' | ' + alt : '') + ')');
      report.push({ block: name, status: 'no section', els: 0 });
    }
    return el;
  }

  /* ============================================================
     PHẦN B – KHAI BÁO TỪNG SECTION
     ============================================================ */

  /**
   * 01. Hero – KHÔNG scrub.
   * Hero nằm trên màn hình đầu tiên, lúc đó chưa có gì để cuộn, nên nó phải
   * tự chạy khi trang load. Đây là ngoại lệ duy nhất đúng của mô hình scrub.
   */
  function initHero() {
    var copy = document.querySelector('.hero__copy');
    if (!copy) { logBad('01 hero', 'KHÔNG TÌM THẤY .hero__copy'); return; }

    var els = qq(copy, '.hero__label, .hero__title, .hero__sub');
    if (els.length === 0) { logBad('01 hero', 'KHÔNG CÓ .hero__label/.hero__title/.hero__sub'); return; }

    gsap.timeline({ delay: 0.15 }).fromTo(els,
      { autoAlpha: 0, y: -d(45) },
      { autoAlpha: 1, y: 0, duration: 1.1, stagger: 0.18, ease: 'power3.out' }
    );
    logOk('01 hero (chạy khi load, không scrub)', 'phần tử=' + els.length);
    report.push({ block: '01 hero', status: 'ok (load)', els: els.length });
  }

  /**
   * 02. Band 19 YEARS – máy đánh chữ, cũng KHÔNG scrub.
   * Chữ viết dở dang khi người dùng dừng giữa chừng sẽ trông như lỗi, nên
   * hiệu ứng này chạy trọn vẹn một lượt.
   */
  function initBand() {
    var band = document.querySelector('.band');
    var pEl = q(band, 'p');
    if (!pEl) { logBad('02 band', 'KHÔNG TÌM THẤY .band p'); return; }

    var fullText = pEl.textContent.trim();
    if (!fullText) { logBad('02 band', '.band p RỖNG'); return; }

    var progress = { count: 0 };
    pEl.textContent = '';

    gsap.timeline({
      scrollTrigger: {
        trigger: band,
        start: 'top 92%',
        toggleActions: 'restart none none reset'
      }
    }).to(progress, {
      count: fullText.length,
      duration: 1.5,
      ease: 'none',
      onUpdate: function () {
        pEl.textContent = fullText.slice(0, Math.floor(progress.count));
      }
    });
    logOk('02 band (typewriter, không scrub)', fullText.length + ' ký tự');
    report.push({ block: '02 band', status: 'ok (typewriter)', els: 1 });
  }

  /** 03. Philosophy – tiêu đề, logo, danh sách thẻ. */
  function initPhilo() {
    var sec = section('03 philo', '.sec.philo', '#philosophy');
    if (!sec) return;

    var head = q(sec, '.sec-head');
    revealDown('03 philo / head', head,
      pick(head, ['.sec-head__title', '.philo__lead-1', '.philo__lead-2']), { stagger: 0.2 });

    var logo = q(sec, '.philo__logo');
    revealDown('03 philo / logo', logo,
      pick(logo, ['.philo__logo-mark', '.philo__logo-text']), { dist: 40, stagger: 0.2 });

    var items = qq(sec, '.philo__item');
    revealUp('03 philo / items', firstOf(q(sec, '.philo__list'), items), items,
      { dist: 30, stagger: 0.12, duration: 0.8 });
  }

  /** 04. Career – tiêu đề, giải thưởng, cúp + pháo hoa, cụm TV, player. */
  function initCareer() {
    var sec = section('04 career', '.sec.career', '#career');
    if (!sec) return;

    var titleWrap = q(sec, '.career__title-wrap');
    revealDown('04 career / title', titleWrap,
      pick(titleWrap, ['.career__arc', '.career__title .l1', '.career__title .l2']));

    var awards = q(sec, '.career__awards');
    revealUp('04 career / awards', awards, qq(awards, '.award'), { dist: 45 });

    var trophyWrap = q(sec, '.career__trophy-wrap');
    reveal({
      name: '04 career / trophy',
      trigger: trophyWrap,
      steps: [
        {
          el: q(trophyWrap, '.career__trophy'),
          from: { autoAlpha: 0, y: d(50), scale: 0.9 },
          to: { autoAlpha: 1, y: 0, scale: 1, duration: 1.15, ease: 'none' }
        },
        {
          el: qq(trophyWrap, '.career__confetti'),
          from: { autoAlpha: 0, scale: 0.6 },
          to: { autoAlpha: 1, scale: 1, duration: 0.9, stagger: 0.15, ease: EASE_POP },
          at: '-=0.4'
        }
      ]
    });

    var tvWrap = q(sec, '.career__tv');
    var liveBadge = q(tvWrap, '.career__live');
    var tvTitle = q(tvWrap, '.career__tv-title');
    revealDown('04 career / tv-head', firstOf(tvTitle, liveBadge, tvWrap),
      [liveBadge, tvTitle].filter(Boolean), { dist: 35, stagger: 0.22 });

    var player = q(sec, '.career__player');
    var apps = qq(sec, '.career__apps img');
    reveal({
      name: '04 career / player',
      trigger: player,
      steps: [
        {
          el: player,
          from: { autoAlpha: 0, y: d(45) },
          to: { autoAlpha: 1, y: 0, duration: 1.1, ease: 'none' }
        },
        {
          el: apps,
          from: { autoAlpha: 0, y: -d(45) },
          to: { autoAlpha: 1, y: 0, duration: 1, stagger: 0.18, ease: 'none' },
          at: '-=0.4'
        }
      ],
      // Nhún trên trục yPercent, KHÔNG dùng y — y đang thuộc quyền tween scrub.
      loop: function () {
        return apps.map(function (img, idx) {
          return gsap.to(img, {
            yPercent: -8, duration: 1.6 + idx * 0.3,
            repeat: -1, yoyo: true, ease: 'sine.inOut'
          });
        });
      }
    });
  }

  /** 05. Program – tiêu đề, tabs, và từng panel (card + mô tả). */
  function initProgram() {
    var sec = section('05 program', '.sec.program', '#program');
    if (!sec) return;

    var head = q(sec, '.sec-head');
    revealDown('05 program / head', head, pick(head, HEAD_PLAIN));

    var tabs = q(sec, '.tabs');
    revealUp('05 program / tabs', tabs, qq(tabs, '.tab'),
      { dist: 35, stagger: 0.12, duration: 0.8 });

    qq(sec, '.program__panel').forEach(function (panel, i) {
      var card = q(panel, '.program__card');
      revealUp('05 program / card#' + i, card, card, { dist: 45, duration: 1.1 });

      var desc = q(panel, '.program__desc');
      revealUp('05 program / desc#' + i, desc, desc, { dist: 30, duration: 0.9 });
    });
  }

  /** 06. Dual Solution – 2 khối text + carousel thiết bị + ảnh therapy. */
  function initDual() {
    var sec = section('06 dual', '.sec.dual', '#dual');
    if (!sec) return;

    var head = q(sec, '.sec-head');
    revealDown('06 dual / head', head, pick(head, HEAD_PLAIN));

    qq(sec, '.dual__block').forEach(function (block, i) {
      reveal({
        name: '06 dual / block#' + i,
        trigger: block,
        steps: [
          {
            el: q(block, 'h3'),
            from: { autoAlpha: 0, y: -d(25) },
            to: { autoAlpha: 1, y: 0, duration: 0.9, ease: 'none' }
          },
          {
            el: qq(block, '.check-list li'),
            from: { autoAlpha: 0, x: -d(30) },
            to: { autoAlpha: 1, x: 0, duration: 0.85, stagger: 0.15, ease: 'none' },
            at: '-=0.4'
          }
        ]
      });
    });

    var devices = q(sec, '.dual__devices');
    reveal({
      name: '06 dual / devices',
      trigger: devices,
      steps: [
        {
          el: devices,
          from: { autoAlpha: 0, y: d(35) },
          to: { autoAlpha: 1, y: 0, duration: 0.9, ease: 'none' }
        },
        {
          el: q(sec, '.dual__plus'),
          from: { autoAlpha: 0, scale: 0.5 },
          to: { autoAlpha: 1, scale: 1, duration: 0.6, ease: EASE_POP },
          at: '-=0.2'
        }
      ]
    });

    var therapy = q(sec, '.dual__therapy');
    revealUp('06 dual / therapy', therapy, therapy, { dist: 35, duration: 0.9 });
  }

  /** 07. Experience Ticket – tiêu đề, tấm vé nhô lên, vòng tròn bung & xoay. */
  function initTicket() {
    var sec = section('07 ticket', '.sec.ticket', '#ticket');
    if (!sec) return;

    var head = q(sec, '.ticket__head');
    revealDown('07 ticket / head', head,
      pick(head, ['.ticket__label', '.ticket__sub', '.ticket__title']));

    var visual = q(sec, '.ticket__visual');
    var circle = q(visual, '.ticket-circle');
    reveal({
      name: '07 ticket / visual',
      trigger: visual,
      steps: [
        {
          el: q(visual, '.ticket-img'),
          from: { autoAlpha: 0, y: d(50), scale: 0.9 },
          to: { autoAlpha: 1, y: 0, scale: 1, duration: 1.1, ease: 'none' }
        },
        {
          // Không đụng tới `rotation` ở đây: rotation thuộc quyền tween xoay
          // vô hạn bên dưới, hai bên ghi cùng lúc là giật.
          el: circle,
          from: { autoAlpha: 0, scale: 0.6 },
          to: { autoAlpha: 1, scale: 1, duration: 0.9, ease: EASE_POP },
          at: '-=0.5'
        }
      ],
      loop: function () {
        if (!circle) return [];
        return [gsap.to(circle, { rotation: 360, duration: 12, repeat: -1, ease: 'none' })];
      }
    });
  }

  /** 08. Consult – tiêu đề & các dòng tư vấn trượt lên lần lượt. */
  function initConsult() {
    var sec = section('08 consult', '.sec.consult', '#consult');
    if (!sec) return;

    var head = q(sec, '.sec-head');
    revealDown('08 consult / head', head, pick(head, HEAD_PLAIN));

    var rows = qq(sec, '.consult__row');
    revealUp('08 consult / rows', firstOf(q(sec, '.consult__list'), rows), rows,
      { dist: 40, stagger: 0.15, duration: 0.95 });
  }

  /** 09. Yoyo ZERO – sec-head nằm NGOÀI section, bảng so sánh nằm trong. */
  function initYoyo() {
    var sec = section('09 yoyo', '.sec.yoyo', '#yoyo');
    if (!sec) return;

    var prev = sec.previousElementSibling;
    var head = (prev && prev.classList.contains('sec-head'))
      ? prev
      : document.querySelector('.sec-head[style*="padding:74px"]');
    revealDown('09 yoyo / head', head, pick(head, HEAD_2LINE));

    reveal({
      name: '09 yoyo / compare',
      trigger: sec,
      steps: [
        {
          el: q(sec, '.compare'),
          from: { autoAlpha: 0, y: d(40), scale: 0.98 },
          to: { autoAlpha: 1, y: 0, scale: 1, duration: 1.1, ease: 'none' }
        },
        {
          el: q(sec, '.swipe-hint'),
          from: { autoAlpha: 0, y: d(25) },
          to: { autoAlpha: 1, y: 0, duration: 0.9, ease: 'none' },
          at: '-=0.4'
        }
      ]
    });
  }

  /** 10. Key Features – 3 feature, mọi phần tử bên trong chạy đồng thời (at: 0). */
  function initFeatures() {
    var sec = section('10 features', '.sec.features', '#features');
    if (!sec) return;

    var HEAD_SELS = ['.sec-head__label', '.sec-head__title', '.feature__desc'];
    function headStep(root) {
      return {
        el: pick(root, HEAD_SELS),
        from: { autoAlpha: 0, y: -d(30) },
        to: { autoAlpha: 1, y: 0, duration: 0.8, stagger: 0.12, ease: 'none' },
        at: 0
      };
    }

    var f1 = q(sec, '.feature--01');
    reveal({
      name: '10 features / 01',
      trigger: f1,
      steps: [
        { el: q(f1, '.feature__bg'), from: { autoAlpha: 0, y: -d(35) }, to: { autoAlpha: 1, y: 0, duration: 0.8, ease: 'none' }, at: 0 },
        { el: q(f1, '.feature__ticket'), from: { autoAlpha: 0, y: -d(35) }, to: { autoAlpha: 1, y: 0, duration: 0.8, ease: 'none' }, at: 0 },
        headStep(f1)
      ]
    });

    var f2 = q(sec, '.feature--02');
    reveal({
      name: '10 features / 02',
      trigger: f2,
      steps: [
        { el: qq(f2, '.thermal__col'), from: { autoAlpha: 0, y: d(30) }, to: { autoAlpha: 1, y: 0, duration: 0.8, stagger: 0.12, ease: 'none' }, at: 0 },
        { el: qq(f2, '.thermal__tag'), from: { autoAlpha: 0 }, to: { autoAlpha: 1, duration: 0.7, stagger: 0.12, ease: 'none' }, at: 0 },
        { el: q(f2, '.thermal__badge'), from: { autoAlpha: 0, scale: 0.6 }, to: { autoAlpha: 1, scale: 1, duration: 0.75, ease: EASE_POP }, at: 0 },
        headStep(f2)
      ]
    });

    var f3 = q(sec, '.feature--03');
    reveal({
      name: '10 features / 03',
      trigger: f3,
      steps: [
        { el: q(f3, '.feature__media'), from: { autoAlpha: 0, y: d(30), scale: 0.96 }, to: { autoAlpha: 1, y: 0, scale: 1, duration: 0.8, ease: 'none' }, at: 0 },
        headStep(f3)
      ]
    });
  }

  /** 11. Thermo Diet – tiêu đề + chip, sân khấu video + bong bóng, claim. */
  function initThermo() {
    var sec = section('11 thermo', '.sec.thermo', '#thermo');
    if (!sec) return;

    var head = q(sec, '.thermo__head');
    var chips = qq(sec, '.thermo__chips .chip');
    reveal({
      name: '11 thermo / head+chips',
      trigger: firstOf(head, chips),
      steps: [
        { el: pick(head, HEAD_PLAIN), from: { autoAlpha: 0, y: -d(35) }, to: { autoAlpha: 1, y: 0, duration: 0.8, stagger: 0.12, ease: 'none' }, at: 0 },
        { el: chips, from: { autoAlpha: 0, y: -d(30) }, to: { autoAlpha: 1, y: 0, duration: 0.8, stagger: 0.1, ease: 'none' }, at: 0 }
      ]
    });

    var stage = q(sec, '.thermo__stage');
    var player = q(stage, '.thermo__player');
    var bubbles = qq(stage, '.bubble');
    reveal({
      name: '11 thermo / stage',
      trigger: firstOf(player, stage),
      steps: [
        { el: player, from: { autoAlpha: 0, y: d(35), scale: 0.96 }, to: { autoAlpha: 1, y: 0, scale: 1, duration: 0.85, ease: 'none' }, at: 0 },
        { el: bubbles, from: { autoAlpha: 0, y: -d(35) }, to: { autoAlpha: 1, y: 0, duration: 0.75, stagger: 0.08, ease: 'none' }, at: 0 }
      ],
      loop: function () {
        return bubbles.map(function (bubble, idx) {
          return gsap.to(bubble, {
            yPercent: -6, duration: 1.5 + (idx % 3) * 0.2,
            repeat: -1, yoyo: true, ease: 'sine.inOut'
          });
        });
      }
    });

    var claim = q(sec, '.thermo__claim');
    revealUp('11 thermo / claim', claim, pick(claim, ['h3', 'p']),
      { dist: 30, stagger: 0.12, duration: 0.8 });
  }

  /** 12–16. Các section chỉ animate phần tiêu đề. */
  var HEAD_ONLY_SECTIONS = [
    { name: '12 meno',    sec: '.sec.meno',    alt: '#menopause', head: '.sec-head',      sels: HEAD_2LINE },
    { name: '13 results', sec: '.sec.results', alt: '#results',   head: '.results__head', sels: HEAD_2LINE },
    { name: '14 turn',    sec: '.sec.turn',    alt: '#turn',      head: '.turn__head',    sels: HEAD_2LINE },
    { name: '15 faq',     sec: '.sec.faq',     alt: '#faq',       head: '.faq__head',     sels: HEAD_PLAIN },
    { name: '16 contact', sec: '.sec.contact', alt: '#contact',   head: '.contact__head', sels: HEAD_2LINE }
  ];

  function initHeadOnlySections() {
    HEAD_ONLY_SECTIONS.forEach(function (item) {
      var sec = section(item.name, item.sec, item.alt);
      if (!sec) return;
      var head = q(sec, item.head) || q(sec, '.sec-head');
      revealDown(item.name + ' / head', head, pick(head, item.sels));
    });
  }

  function buildAll() {
    report.length = 0;
    initHero();
    initBand();
    initPhilo();
    initCareer();
    initProgram();
    initDual();
    initTicket();
    initConsult();
    initYoyo();
    initFeatures();
    initThermo();
    initHeadOnlySections();
    printSummary();
  }

  /** Bảng tổng kết sau khi dựng xong. */
  function printSummary() {
    if (!DEBUG) return;
    var ok = report.filter(function (r) { return r.status.indexOf('ok') === 0; }).length;
    var bad = report.length - ok;
    logInfo('TỔNG KẾT: %d block OK, %d block LỖI, %d ScrollTrigger đang sống',
      ok, bad, ScrollTrigger.getAll().length);

    if (bad > 0) {
      console.table(report.filter(function (r) { return r.status.indexOf('ok') !== 0; })
        .map(function (r) { return { block: r.block, 'vấn đề': r.status }; }));
    }

    // Vị trí thực tế của từng trigger, tính bằng pixel tuyệt đối trong trang.
    window.btlAnimReport = function () {
      console.table(report.filter(function (r) { return r.st; }).map(function (r) {
        return {
          block: r.block,
          'phần tử': r.els,
          'start (px)': Math.round(r.st.start),
          'end (px)': Math.round(r.st.end),
          'tiến độ': r.st.progress.toFixed(2)
        };
      }));
    };
    logInfo('Gõ btlAnimReport() trong console để xem vị trí px của từng trigger.');
  }

  /* ============================================================
     PHẦN C – KHỞI ĐỘNG
     ============================================================ */

  /**
   * ScrollTrigger tính toạ độ theo layout tại thời điểm tạo. Trang này có rất
   * nhiều ảnh không khai báo kích thước, nên layout còn dịch hàng nghìn pixel
   * SAU khi script chạy. Mỗi lần layout đổi mà không refresh là một lần
   * animation chạy sai chỗ.
   */
  function setupRefreshGuards() {
    var pending = null;
    var n = 0;

    function refreshSoon(why) {
      if (pending) clearTimeout(pending);
      pending = setTimeout(function () {
        pending = null;
        // Ghim về đầu TRƯỚC khi đo lại, để mọi trigger được tính trên đúng
        // layout cuối cùng và ở đúng mốc scroll = 0.
        forceTop();
        ScrollTrigger.refresh();
        logInfo('refresh #%d (%s) – chiều cao trang: %dpx',
          ++n, why || '?', document.documentElement.scrollHeight);
      }, 120);
    }

    var imgs = gsap.utils.toArray(document.querySelectorAll('img'));
    var pendingImgs = imgs.filter(function (i) { return !i.complete; });
    logInfo('ảnh: %d tổng, %d chưa tải xong', imgs.length, pendingImgs.length);

    pendingImgs.forEach(function (img) {
      img.addEventListener('load', function () { refreshSoon('ảnh'); }, { once: true });
      img.addEventListener('error', function () { refreshSoon('ảnh lỗi'); }, { once: true });
    });

    if (document.fonts && document.fonts.ready) {
      document.fonts.ready.then(function () { refreshSoon('font'); }).catch(function () {});
    }

    // Chỉ đăng ký listener là KHÔNG đủ: nếu script chạy sau khi 'load' đã bắn
    // (defer / bfcache / trang trong cache) thì listener không bao giờ chạy.
    if (document.readyState === 'complete') {
      refreshSoon('đã complete');
    } else {
      window.addEventListener('load', function () {
        forceTop();          // ngay lập tức, không chờ debounce
        refreshSoon('load');
      }, { once: true });
    }

    window.addEventListener('pageshow', function (e) {
      if (e.persisted) { forceTop(); refreshSoon('bfcache'); }
    });
  }

  function start() {
    if (typeof gsap === 'undefined') {
      console.error('[BTL] gsap CHƯA ĐƯỢC NẠP — kiểm tra thẻ <script> gsap.min.js');
      return;
    }
    if (typeof ScrollTrigger === 'undefined') {
      console.error('[BTL] ScrollTrigger CHƯA ĐƯỢC NẠP — kiểm tra thẻ <script> ScrollTrigger.min.js');
      return;
    }

    gsap.registerPlugin(ScrollTrigger);
    ScrollTrigger.config({ ignoreMobileResize: true });
    // Xoá vị trí cuộn mà ScrollTrigger tự nhớ giữa các lần load, và bảo nó
    // đừng nhờ trình duyệt khôi phục.
    if (!HAS_HASH) ScrollTrigger.clearScrollMemory('manual');
    gsap.defaults({ ease: 'none' });

    forceTop();

    logInfo('GSAP %s | ScrollTrigger OK | readyState=%s | viewport=%dx%d',
      gsap.version, document.readyState, window.innerWidth, window.innerHeight);

    gsap.matchMedia().add({
      isMobile: '(max-width: 767px)',
      isDesktop: '(min-width: 768px)',
      reduce: '(prefers-reduced-motion: reduce)'
    }, function (ctx) {
      if (ctx.conditions.reduce) {
        logInfo('prefers-reduced-motion BẬT → bỏ qua toàn bộ animation');
        return;
      }
      M = ctx.conditions.isMobile ? MOTION.mobile : MOTION.desktop;
      logInfo('breakpoint=%s | start=%s end=%s scrub=%s scale=%s',
        ctx.conditions.isMobile ? 'mobile' : 'desktop', M.start, M.end, M.scrub, M.scale);
      buildAll();
    });

    setupRefreshGuards();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', start);
  } else {
    start();
  }
})();
