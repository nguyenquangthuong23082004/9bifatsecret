/* BITIEL landing – interactions */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {

    /* ---- GNB mobile menu ---- */
    var toggle = document.querySelector('.gnb__toggle');
    var menu = document.getElementById('gnbMenu');
    if (toggle && menu) {
      var gnb = toggle.closest('.gnb');
      var setMenu = function (open) {
        if (open) { menu.removeAttribute('hidden'); } else { menu.setAttribute('hidden', ''); }
        toggle.setAttribute('aria-expanded', String(open));
        toggle.setAttribute('aria-label', open ? '메뉴 닫기' : '메뉴 열기');
        if (gnb) gnb.classList.toggle('is-open', open);
        document.body.style.overflow = open ? 'hidden' : '';
      };
      toggle.addEventListener('click', function () {
        setMenu(menu.hasAttribute('hidden'));
      });
      menu.querySelectorAll('a').forEach(function (a) {
        a.addEventListener('click', function () { setMenu(false); });
      });
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !menu.hasAttribute('hidden')) { setMenu(false); }
      });
    }

    /* ---- Program tabs ---- */
    var tabs = document.querySelectorAll('.program .tab');
    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        tabs.forEach(function (t) {
          var isCurrent = t === tab;
          t.classList.toggle('is-active', isCurrent);
          t.setAttribute('aria-selected', isCurrent ? 'true' : 'false');
          var target = document.getElementById(t.getAttribute('aria-controls'));
          if (target) { target.hidden = !isCurrent; }
        });
      });
    });

    /* ---- Menopause: chọn 1 mục (chỉ đổi style is-open) ----
       Design mới (Figma 529:580) bỏ .acc__panel mở/đóng, khối chi tiết
       .meno__care là tĩnh — nên click chỉ chuyển trạng thái active giữa
       các .acc__item, và luôn giữ đúng 1 mục được chọn. */
    var accItems = document.querySelectorAll('.accordion-btl .acc__item');
    accItems.forEach(function (item) {
      var head = item.querySelector('.acc__head');
      if (!head) return;
      head.addEventListener('click', function () {
        accItems.forEach(function (i) {
          i.classList.remove('is-open');
          var h = i.querySelector('.acc__head');
          if (h) h.setAttribute('aria-expanded', 'false');
        });
        item.classList.add('is-open');
        head.setAttribute('aria-expanded', 'true');
      });
    });

    /* ---- FAQ accordion (single-open) ---- */
    var faqItems = document.querySelectorAll('.faq__item');
    faqItems.forEach(function (item) {
      var q = item.querySelector('.faq__q');
      if (!q) return;
      q.addEventListener('click', function () {
        var isOpen = item.classList.contains('is-open');
        faqItems.forEach(function (i) {
          i.classList.remove('is-open');
          var b = i.querySelector('.faq__q');
          if (b) b.setAttribute('aria-expanded', 'false');
        });
        if (!isOpen) {
          item.classList.add('is-open');
          q.setAttribute('aria-expanded', 'true');
        }
      });
    });

    /* ---- Results: main tabs (전신/체형/부분/일상) ---- */
    var rtabs = document.querySelectorAll('.results .rtab');
    rtabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        rtabs.forEach(function (t) {
          t.classList.remove('is-active');
          t.setAttribute('aria-selected', 'false');
        });
        tab.classList.add('is-active');
        tab.setAttribute('aria-selected', 'true');
        document.querySelectorAll('.results .rpanel').forEach(function (p) {
          p.classList.remove('is-active');
        });
        var panel = document.getElementById(tab.getAttribute('data-panel'));
        if (panel) panel.classList.add('is-active');
      });
    });

    /* ---- Results: Subhead block switcher (chuyển đổi rblock 1 & 2 khi click subhead title) ---- */
    document.querySelectorAll('.results .rpanel').forEach(function (panel) {
      var blocks = panel.querySelectorAll('.rblock');
      if (blocks.length > 1) {
        // Mặc định hiện rblock đầu tiên, ẩn các rblock còn lại trong panel
        blocks.forEach(function (b, idx) {
          b.style.display = idx === 0 ? 'block' : 'none';
        });

        // Đăng ký sự kiện click cho các subhead title span
        blocks.forEach(function (block) {
          var subheadSpans = block.querySelectorAll('.subhead span');
          subheadSpans.forEach(function (span, targetIdx) {
            span.addEventListener('click', function () {
              blocks.forEach(function (b, bIdx) {
                // Hiển thị rblock tương ứng với title được click
                b.style.display = bIdx === targetIdx ? 'block' : 'none';

                // Cập nhật class 'is-on' cho title active và xóa khỏi title inactive
                var headSpans = b.querySelectorAll('.subhead span');
                headSpans.forEach(function (s, sIdx) {
                  if (sIdx === targetIdx) {
                    s.classList.add('is-on');
                  } else {
                    s.classList.remove('is-on');
                  }
                });
              });
            });
          });
        });
      }
    });

    /* ---- Device carousel (Swiper) ---- */
    var devices = document.querySelector('.dual__devices');
    if (window.Swiper && devices) {
      var deviceCount = devices.querySelectorAll('.swiper-slide').length;
      new window.Swiper(devices, {
        slidesPerView: 'auto',
        centeredSlides: true,
        // mở trang là dải slide đã nằm giữa (slide ở chính giữa được chọn sẵn)
        initialSlide: Math.floor((deviceCount - 1) / 2),
        spaceBetween: 20,
        grabCursor: true
      });
    }

    /* ---- Your turn: before/after case carousel (Swiper) ---- */
    if (window.Swiper && document.querySelector('.turn__swiper')) {
      new window.Swiper('.turn__swiper', {
        slidesPerView: 1,     // one case per screen — no neighbour peeking
        spaceBetween: 0,
        grabCursor: true,
        pagination: { el: '.turn__dots', clickable: true },
        navigation: {
          nextEl: '.turn__nav--next',
          prevEl: '.turn__nav--prev'
        }
      });
    }

    /* ---- Video placeholder play ---- */
    var player = document.querySelector('.career__player');
    if (player) {
      player.addEventListener('click', function () {
        var url = player.getAttribute('data-video');
        if (url) { window.open(url, '_blank', 'noopener'); }
      });
    }
  });
})();
