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

  /**
   * SETTLED = người dùng đã cầm lái. Từ thời điểm này trở đi, KHÔNG được phép
   * tự ý dời vị trí cuộn của họ nữa, dù với bất kỳ lý do gì.
   *
   * Cột mốc này là bắt buộc, không phải phòng xa: ảnh trong trang đều
   * loading="lazy" nên chúng tải rải rác trong lúc người dùng cuộn, mỗi lần
   * tải xong lại kéo theo một lần ScrollTrigger.refresh(). Nếu refresh nào
   * cũng ép về đầu trang thì người dùng cuộn tới đâu sẽ bị đá về đầu tới đó.
   */
  var SETTLED = false;

  function markSettled() { SETTLED = true; }

  ['wheel', 'touchstart', 'keydown', 'pointerdown'].forEach(function (evt) {
    window.addEventListener(evt, markSettled, { once: true, passive: true });
  });

  /** Ghim về đầu trang. Chỉ có tác dụng trong giai đoạn trang đang tự dựng. */
  function forceTop() {
    if (HAS_HASH || SETTLED) return;
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
  } catch (e) { }

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
    desktop: { start: 'top 90%', end: 'top 40%', scrub: 1, scale: 1 },
    mobile: { start: 'top 95%', end: 'top 45%', scrub: 0.8, scale: 0.55 }
  };

  var M = MOTION.desktop;   // token đang áp dụng, do matchMedia gán

  /** Có được phép ghim (pin) hay không — do matchMedia gán. */
  var CAN_PIN = false;

  /** Quy đổi khoảng cách px theo breakpoint. */
  function d(px) {
    return Math.round(px * M.scale);
  }

  /**
   * Đẩy phần tử lên layer riêng của GPU.
   *
   * Cần cho những khối bị scrub liên tục: mỗi khung hình cuộn là một lần trình
   * duyệt phải vẽ lại. Nếu phần tử còn nằm trong một khung có `overflow:hidden`
   * kèm `border-radius` thì trình duyệt phải cắt tròn góc lại từ đầu ở MỖI
   * khung hình — đó chính là cảm giác giật.
   *
   * `will-change: transform` bảo trình duyệt tách phần tử ra một lớp riêng và
   * nướng sẵn phần cắt gọt đó một lần; `force3D` ép GSAP dùng translate3d để
   * chuyển động chạy trên GPU thay vì CPU.
   */
  function gpu(els) {
    if (isEmpty(els)) return;
    gsap.set(els, { willChange: 'transform', force3D: true });
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
        // Ghim: quãng cuộn start→end không đẩy trang đi nữa mà được tiêu vào
        // việc chạy timeline. Truyền vào phần tử cụ thể chứ không phải `true`,
        // để chọn đúng thứ được bọc bởi pin-spacer.
        pin: config.pin || false,
        // Chừa khoảng trống bằng đúng quãng ghim để nội dung dưới không bị đè.
        pinSpacing: true,
        // Chuyển sang position:fixed sớm 1 nhịp, tránh giật ở khoảnh khắc ghim.
        anticipatePin: config.pin ? 1 : 0,
        // Neo về trạng thái sạch khi người dùng dừng tay. Cần cho khối bị ghim:
        // cuộn nhanh làm animation tụt lại phía sau, snap kéo nó về đúng một
        // trong các mốc đã định thay vì để nằm dở dang giữa hai nhịp.
        snap: config.snap || false,
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
   * 01 + 02. Hero (.hero__copy) và Band 19 YEARS (.band p) – MỘT timeline.
   *
   * Cả hai đều nằm ở đỉnh trang (scrollY = 0), nơi chưa tồn tại quãng cuộn nào
   * để buộc animation vào — đây là ngoại lệ duy nhất đúng của mô hình scrub.
   * Chúng chạy khi trang load, theo một mạch liền: chữ hero trượt xuống, rồi
   * chữ band gõ ra nối tiếp.
   *
   * "Không scrub được" khác với "không lặp lại được": cuộn xuống rồi quay lên
   * vẫn là sự kiện bắt được bình thường, nên cả cụm vẫn chạy lại.
   *
   * Trigger là .hero (KHÔNG phải .hero__copy): trên mobile .hero__copy dùng
   * display:contents nên bounding-box = 0, ScrollTrigger sẽ đo sai.
   */
  function initHeroBand() {
    var copy = document.querySelector('.hero__copy');
    if (!copy) { logBad('01 hero', 'KHÔNG TÌM THẤY .hero__copy'); return; }

    var heroSec = document.querySelector('.hero') || copy;

    var els = qq(copy, '.hero__label, .hero__title, .hero__sub');
    if (els.length === 0) { logBad('01 hero', 'KHÔNG CÓ .hero__label/.hero__title/.hero__sub'); return; }

    /* --- Chuẩn bị chữ cho band -------------------------------------------
       Đây là khối DUY NHẤT ghi đè lên nội dung thật của trang, nên phải cẩn
       thận hơn mọi khối khác: chuỗi gốc cất vào data-attribute chứ KHÔNG đọc
       lại từ textContent — vì textContent đã bị chính hiệu ứng này xoá, lần
       dựng lại sau (đổi breakpoint) sẽ đọc phải chuỗi rỗng và mất chữ vĩnh
       viễn.                                                                */
    var pEl = q(document.querySelector('.band'), 'p');
    var fullText = '';

    if (pEl) {
      fullText = pEl.getAttribute('data-btl-text');
      if (fullText === null) {
        fullText = pEl.textContent.trim();
        pEl.setAttribute('data-btl-text', fullText);
      }
      if (!fullText) { logBad('02 band', '.band p RỖNG'); pEl = null; }
    } else {
      logBad('02 band', 'KHÔNG TÌM THẤY .band p');
    }

    function write(n) { if (pEl) pEl.textContent = fullText.slice(0, n); }

    /* --- Timeline chung --------------------------------------------------- */
    var progress = { count: 0 };
    var tl = gsap.timeline({ paused: true });

    tl.fromTo(els,
      { autoAlpha: 0, y: -d(45) },
      { autoAlpha: 1, y: 0, duration: 1.1, stagger: 0.18, ease: 'power3.out' }
    );

    if (pEl) {
      // '-=0.6': chữ band bắt đầu gõ khi hero còn đang trượt, để hai khối nối
      // vào nhau thành một mạch thay vì hai lượt rời rạc.
      tl.to(progress, {
        count: fullText.length,
        duration: 1.5,
        ease: 'none',
        onUpdate: function () { write(Math.floor(progress.count)); }
      }, '-=0.6');
    }

    function play() { write(0); tl.restart(); }

    /* Nạp đạn cho lượt chạy sau: ẩn chữ hero, NHƯNG giữ nguyên chữ band.
       Band là nội dung thật của trang và nó nằm ngay dưới hero — khi người
       dùng cuộn qua khỏi hero thì band vẫn đang hiển thị trên màn hình, xoá
       chữ lúc đó là làm mất nội dung ngay trước mắt họ. */
    function reset() { tl.pause(0); write(fullText.length); }

    tl.pause(0); write(0);            // trạng thái ẩn trước khi vẽ khung hình đầu
    gsap.delayedCall(0.15, play);     // lượt chạy đầu tiên khi vào trang

    /* --- Cho phép chạy lại -----------------------------------------------
       Chủ động dùng callback thay vì toggleActions: ở đỉnh trang, trigger đã
       nằm sau mốc start ngay từ khi tạo, nên không thể trông chờ onEnter bắn.
       Lưu ý: ScrollTrigger ở đây CHỈ dùng để chạy lại khi quay về đầu trang.
       Lượt chạy đầu tiên hoàn toàn không phụ thuộc scrollY. */
    ScrollTrigger.create({
      trigger: heroSec,
      start: 'top bottom',
      end: 'bottom top',
      onLeave: reset,
      onEnterBack: function () {
        logInfo('▶ 01+02 hero + band chạy lại');
        play();
      }
    });

    logOk('01+02 hero + band (một timeline, chạy khi load + lặp lại)',
      'hero=' + els.length + ' phần tử, band=' + (pEl ? fullText.length + ' ký tự' : 'không có'));
    report.push({ block: '01+02 hero + band', status: 'ok (load+replay)', els: els.length + (pEl ? 1 : 0) });
  }

  /** 03. Philosophy – tiêu đề, logo, danh sách thẻ. */
  function initPhilo() {
    var sec = section('03 philo', '.sec.philo', '#philosophy');
    if (!sec) return;

    var head = q(sec, '.sec-head');
    revealDown('03 philo / head', head,
      pick(head, ['.sec-head__title', '.philo__lead-1', '.philo__lead-2']), {dist: 200, stagger: 0.2 });

    // Logo và danh sách thẻ dùng CHUNG một timeline, mọi bước đặt tại `at: 0`
    // nên chúng chạy đồng thời. Trigger là .philo__logo vì nó nằm trên trong
    // DOM — khối đứng trước quyết định thời điểm cho cả cụm.
    var logo = q(sec, '.philo__logo');
    var items = qq(sec, '.philo__item');
    reveal({
      name: '03 philo / logo + items (đồng thời)',
      trigger: firstOf(logo, q(sec, '.philo__list'), items),
      steps: [
        {
          el: q(logo, '.philo__logo-mark'),
          from: { autoAlpha: 0, y: -d(100) },
          to: { autoAlpha: 1, y: 0, duration: 0.9, ease: 'none' },
          at: 0
        },
        {
          el: q(logo, '.philo__logo-text'),
          from: { autoAlpha: 0, y: -d(100) },
          to: { autoAlpha: 1, y: 0, duration: 0.9, ease: 'none' },
          at: 0
        },
        {
          el: items,
          from: { autoAlpha: 0, y: d(90) },
          to: { autoAlpha: 1, y: 0, duration: 0.9, stagger: 0.12, ease: 'none' },
          at: 0
        }
      ]
    });
  }

  /** 04. Career – tiêu đề, giải thưởng, cúp + pháo hoa, cụm TV, player. */
  function initCareer() {
    var sec = section('04 career', '.sec.career', '#career');
    if (!sec) return;

    var titleWrap = q(sec, '.career__title-wrap');
    revealDown('04 career / title', titleWrap,
      pick(titleWrap, ['.career__arc', '.career__title .l1', '.career__title .l2']));

    var awards = q(sec, '.career__awards');
    revealUp('04 career / awards', awards, qq(awards, '.award'), { dist: 100 });

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

    /* --- Feature 01 --------------------------------------------------------
       .feature__media ở đây KHÔNG trong suốt: nó có nền xám #f5f5f5, bo góc
       20px và overflow:hidden. Cái khung đó luôn hiện sẵn trên trang.

       Hai hệ quả quyết định cách làm khối này:

       1. KHÔNG dùng fade. Ảnh mờ dần trên nền xám nhạt thì mắt thường gần như
          không thấy gì. Thay vào đó cho ảnh trượt hẳn từ ngoài khung vào —
          overflow:hidden biến chuyển động đó thành một cú "đổ xuống" rõ ràng,
          nhìn thấy được bất kể màu nền.
          Vì vậy dùng yPercent (tính theo chiều cao của chính ảnh) chứ không
          phải px: -100% là ảnh nằm trọn phía trên mép khung, bị cắt sạch.

       2. Được phép bắt đầu MUỘN. Khung xám đã hiện sẵn nên không sợ khoảng
          trống; ta chờ tới khi người dùng thật sự nhìn vào khung rồi mới cho
          ảnh đổ xuống. Bản trước bắt đầu ở 'top 90%' nên chạy xong từ lúc
          khối còn chưa vào tầm mắt.                                          */
    var f1 = q(sec, '.feature--01');
    var media1 = q(f1, '.feature__media');
    var bg1 = q(f1, '.feature__bg');
    var ticket1 = q(f1, '.feature__ticket');

    // Cả khung lẫn hai ảnh đều lên layer GPU. Khung phải lên cùng, vì chính
    // nó mới là thứ giữ border-radius + overflow:hidden.
    gpu([media1, bg1, ticket1].filter(Boolean));

    /* --- Ghim: cuộn khựng lại chờ vé rơi xong ------------------------------
       Ảnh nền đổ xuống vào chỗ, rồi trang ngừng trôi cho tới khi tấm vé rơi
       xong mới đi tiếp. Cả hai vẫn bám tay người dùng (scrub) — chỉ khác là
       không thể cuộn vượt qua giữa chừng.

       BỐN CHỐT AN TOÀN, mỗi cái chặn đúng một lỗi đã gặp:

       1. Ghim `.feature--01`, KHÔNG ghim `.feature__media`.
          ScrollTrigger bọc phần tử bị ghim vào <div class="pin-spacer"> và
          chuyển margin sang thẻ bọc. `.feature--01 .feature__media` đang dựa
          vào margin-top:16px, margin âm -7px/-8px và aspect-ratio — bọc nó là
          cả bốn thứ đó lệch. `.feature--01` thì không có margin nào, và đã
          kiểm tra: không CSS nào dùng combinator con/anh-em (`>`, `+`, `~`)
          tới nó, `.sec.features` cũng chỉ là block thường (position:relative),
          nên chèn thêm một thẻ bọc không đổi gì.

       2. start 'top top' — khối áp sát mép trên trong suốt lúc ghim. Mốc khác
          sẽ để lại một dải trống đứng im trước mắt suốt vài giây.

       3. Tự tắt khi khối cao hơn màn hình. Ghim đỉnh khối vào mép trên trong
          khi khối cao hơn viewport = phần dưới bị cắt cụt mà không cuộn xuống
          xem được, vì trang đang bị giữ.

       4. Tự tắt trên mobile và màn hình thấp (CAN_PIN).                       */
    var pinFits = !!(f1 && f1.offsetHeight <= window.innerHeight * 0.95);
    var doPin = CAN_PIN && pinFits;

    if (CAN_PIN && !pinFits) {
      logInfo('10 features / 01: BỎ ghim — khối cao %dpx > màn hình %dpx',
        f1 ? f1.offsetHeight : 0, window.innerHeight);
    }

    reveal({
      name: '10 features / 01 media' + (doPin ? ' (ghim)' : ''),
      trigger: doPin ? f1 : media1,
      start: doPin ? 'top top' : 'top 78%',
      // Hàm chứ không phải chuỗi: quãng ghim được tính lại mỗi lần refresh,
      // nên đổi cỡ cửa sổ hay xoay màn hình vẫn đúng.
      end: doPin
        ? function () { return '+=' + Math.round(window.innerHeight * 0.9); }
        : 'bottom 45%',
      pin: doPin ? f1 : false,
      // Khi ghim thì bám SÁT tay (0.4), không lười (1.2).
      // `scrub` là số GIÂY TRỄ. Cuộn chậm thì độ trễ lớn đọc ra là "mượt";
      // cuộn nhanh thì vị trí cuộn nhảy vọt còn animation lê phía sau, phải
      // đuổi bù cả một quãng dài sau khi người dùng đã buông tay — và vì trang
      // đang bị ghim đứng yên nên không có gì che lấp độ lệch đó.
      scrub: doPin ? 0.4 : 1.2,
      // Người dùng buông tay ở đâu thì kéo về mốc gần nhất trong ba mốc sạch:
      //   0   = chưa gì cả
      //   0.5 = ảnh nền vừa vào chỗ (timeline dài 2, nền chiếm nửa đầu)
      //   1   = vé đã rơi xong
      // Nhờ vậy cuộn nhanh mấy cũng không để lại trạng thái dở dang giữa nhịp.
      snap: doPin ? {
        snapTo: [0, 0.5, 1],
        duration: { min: 0.15, max: 0.45 },
        delay: 0.05,
        ease: 'power2.inOut'
      } : false,
      steps: [
        {
          // Ảnh nền đổ xuống TRƯỚC — nó là bối cảnh, lớp dưới cùng.
          // ease 'none': với tween bị scrub, đường cong easing chồng lên độ
          // trễ của scrub tạo ra hai lớp gia/giảm tốc đánh nhau — mắt đọc ra
          // thành giật. Tốc độ ở đây đã do chính tay người dùng quyết định.
          el: bg1,
          from: { yPercent: -100 },
          to: { yPercent: 0, duration: 1, ease: 'none' },
          at: 0
        },
        {
          // '>' = bắt đầu đúng lúc bước trước kết thúc, không chồng lấn.
          // Vé đi xa hơn nền (-140% so với -100%) để tách lớp.
          el: ticket1,
          from: { yPercent: -140 },
          to: { yPercent: 0, duration: 1, ease: 'none' },
          at: '>'
        }
      ]
    });

    // Cụm chữ có trigger RIÊNG, đo trên chính nó. Trước đây nó dùng chung
    // trigger với khung ảnh nên cũng chạy xong trước khi người dùng đọc tới.
    reveal({
      name: '10 features / 01 head',
      trigger: q(f1, '.sec-head'),
      steps: [headStep(f1)]
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
    { name: '12 meno', sec: '.sec.meno', alt: '#menopause', head: '.sec-head', sels: HEAD_2LINE },
    { name: '13 results', sec: '.sec.results', alt: '#results', head: '.results__head', sels: HEAD_2LINE },
    { name: '14 turn', sec: '.sec.turn', alt: '#turn', head: '.turn__head', sels: HEAD_2LINE },
    { name: '15 faq', sec: '.sec.faq', alt: '#faq', head: '.faq__head', sels: HEAD_PLAIN },
    { name: '16 contact', sec: '.sec.contact', alt: '#contact', head: '.contact__head', sels: HEAD_2LINE }
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
    initHeroBand();
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
      document.fonts.ready.then(function () { refreshSoon('font'); }).catch(function () { });
    }

    // Chỉ đăng ký listener là KHÔNG đủ: nếu script chạy sau khi 'load' đã bắn
    // (defer / bfcache / trang trong cache) thì listener không bao giờ chạy.
    //
    // 'load' cũng là mốc kết thúc giai đoạn trang tự dựng: mọi ảnh không-lazy
    // đã có kích thước thật, chiều cao trang đã ổn định. Từ đây trở đi trang
    // thuộc về người dùng — những lần refresh sau (ảnh lazy tải dần trong lúc
    // cuộn) vẫn chạy bình thường nhưng không được đụng vào vị trí cuộn nữa.
    if (document.readyState === 'complete') {
      forceTop();
      refreshSoon('đã complete');
      markSettled();
    } else {
      window.addEventListener('load', function () {
        forceTop();          // ngay lập tức, không chờ debounce
        refreshSoon('load');
        markSettled();
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
      // Chiều cao cũng là breakpoint. Việc ghim có khả thi hay không phụ thuộc
      // hoàn toàn vào nó, nên khai báo ở đây để GSAP dựng lại (và đánh giá lại
      // điều kiện ghim) khi người dùng đổi cỡ cửa sổ qua mốc này.
      isShort: '(max-height: 820px)',
      reduce: '(prefers-reduced-motion: reduce)'
    }, function (ctx) {
      var c = ctx.conditions;

      if (c.reduce) {
        logInfo('prefers-reduced-motion BẬT → bỏ qua toàn bộ animation');
        return;
      }

      M = c.isMobile ? MOTION.mobile : MOTION.desktop;

      // Ghim là thao tác đắt nhất của ScrollTrigger (chuyển position:fixed qua
      // lại + chèn thẻ bọc). Chỉ cho phép ở nơi nó chắc chắn an toàn.
      CAN_PIN = !c.isMobile && !c.isShort;

      logInfo('breakpoint=%s | start=%s end=%s scrub=%s scale=%s | màn hình cao %dpx | ghim=%s',
        c.isMobile ? 'mobile' : 'desktop', M.start, M.end, M.scrub, M.scale,
        window.innerHeight, CAN_PIN ? 'CHO PHÉP' : 'TẮT');
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
