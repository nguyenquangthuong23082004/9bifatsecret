/**
 * ============================================================================
 * BTL Landing Page - Comprehensive Pure JavaScript Scroll Animations
 * (Loại bỏ 100% hiệu ứng hover nổi box cho Section Results .case)
 * ============================================================================
 */

(function () {
  'use strict';

  // 1. Tự động chèn CSS Styles cho hiệu ứng rơi từ trên xuống & nhoi từ dưới lên (Tốc độ mượt 1.4s)
  function injectAnimationStyles() {
    if (document.getElementById('btl-pure-anim-styles')) return;
    var style = document.createElement('style');
    style.id = 'btl-pure-anim-styles';
    style.textContent = `
      /* ================================================================
         SECTION 1: CSS Keyframes Native - Tốc độ mượt 1.4s
         ================================================================ */
      @keyframes heroSlideDown {
        0% {
          opacity: 0;
          transform: translateY(-50px);
        }
        100% {
          opacity: 1;
          transform: translateY(0);
        }
      }

      @keyframes heroSlideUp {
        0% {
          opacity: 0;
          transform: translateY(50px);
        }
        100% {
          opacity: 1;
          transform: translateY(0);
        }
      }

      .hero__label {
        animation: heroSlideDown 1.4s cubic-bezier(0.16, 1, 0.3, 1) 0.15s both !important;
      }
      .hero__title {
        animation: heroSlideDown 1.4s cubic-bezier(0.16, 1, 0.3, 1) 0.35s both !important;
      }
      .hero__sub {
        animation: heroSlideDown 1.4s cubic-bezier(0.16, 1, 0.3, 1) 0.55s both !important;
      }
      .hero__visual {
        animation: heroSlideUp 1.4s cubic-bezier(0.16, 1, 0.3, 1) 0.45s both !important;
      }

      /* ================================================================
         CÁC SECTION 2 -> 15: IntersectionObserver Scroll Animations
         Thời gian chuyển động nâng lên 1.4s cho độ lướt trôi êm ái, sang trọng
         ================================================================ */
      .btl-anim {
        opacity: 0;
        will-change: transform, opacity;
        transition: opacity 1.4s cubic-bezier(0.16, 1, 0.3, 1),
                    transform 1.4s cubic-bezier(0.16, 1, 0.3, 1);
      }

      /* 1. RƠI TỪ TRÊN XUỐNG DƯỚI (Falling / Dropping from top) */
      .anim-down { transform: translateY(-50px); }

      /* 2. NHOI TỪ DƯỚI LÊN TRÊN (Rising / Popping from bottom) */
      .anim-up { transform: translateY(50px); }

      /* 3. Trượt từ trái / từ phải qua */
      .anim-left { transform: translateX(-50px); }
      .anim-right { transform: translateX(50px); }

      /* 4. Phóng to hiện ra nhẹ nhàng */
      .anim-scale { transform: scale(0.92); }

      /* Hiệu ứng pháo hoa nổ bung bên trái (Confetti Burst Left) */
      .anim-pop-l {
        opacity: 0;
        transform: scale(0.2) translate(40px, 30px);
        will-change: transform, opacity;
        transition: opacity 0.95s cubic-bezier(0.175, 0.885, 0.32, 1.275),
                    transform 0.95s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      }
      .anim-pop-l.in-view {
        opacity: 1 !important;
        transform: scale(1) translate(0, 0) !important;
      }

      /* Hiệu ứng pháo hoa nổ bung bên phải (Confetti Burst Right) */
      .anim-pop-r {
        opacity: 0;
        transform: scale(0.2) translate(-40px, 30px);
        will-change: transform, opacity;
        transition: opacity 0.95s cubic-bezier(0.175, 0.885, 0.32, 1.275),
                    transform 0.95s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      }
      .anim-pop-r.in-view {
        opacity: 1 !important;
        transform: scale(1) translate(0, 0) !important;
      }

      /* Chỉ animate opacity (Fade-only) để KHÔNG ghi đè CSS transform gốc -> Giữ layout chuẩn 100% */
      .anim-fade {
        opacity: 0;
        will-change: opacity;
        transition: opacity 1.4s cubic-bezier(0.16, 1, 0.3, 1);
      }
      .anim-fade.in-view {
        opacity: 1 !important;
      }

      /* Trạng thái khi cuộn tới tầm nhìn */
      .btl-anim.in-view:not(.anim-fade):not(.anim-pop-l):not(.anim-pop-r):not(.anim-bounce-icon):not(.anim-bubble) {
        opacity: 1 !important;
        transform: translate(0, 0) scale(1) !important;
      }

      /* ================================================================
         2 ICON APP SECTION 3: RƠI TỪ TRÊN XUỐNG DƯỚI RỒI NHẢY TƯNG TƯNG
         ================================================================ */
      @keyframes iconBounceContinuous {
        0%, 100% {
          transform: translateY(0) scale(1);
        }
        50% {
          transform: translateY(-14px) scale(1.06);
        }
      }

      .anim-bounce-icon {
        opacity: 0;
        transform: translateY(-65px) scale(0.85);
        will-change: transform, opacity;
        transition: opacity 0.95s cubic-bezier(0.16, 1, 0.3, 1);
      }

      .anim-bounce-icon.in-view {
        opacity: 1 !important;
        animation: iconBounceContinuous 2.4s ease-in-out infinite !important;
      }

      /* ================================================================
         BONG BÓNG THERMO: HIỆN RA RỒI NHÚN NHẢY LIÊN TỤC
         ================================================================ */
      @keyframes bubbleFloat {
        0%, 100% {
          transform: translateY(0) scale(1);
        }
        50% {
          transform: translateY(-12px) scale(1.03);
        }
      }

      .anim-bubble {
        opacity: 0;
        will-change: transform, opacity;
        transition: opacity 1s cubic-bezier(0.16, 1, 0.3, 1);
      }

      .anim-bubble.in-view {
        opacity: 1 !important;
        animation: bubbleFloat 3s ease-in-out infinite !important;
      }

      /* Lệch pha từng bong bóng — nếu cả 6 cái cùng lên cùng xuống thì nhìn
         như một khối cứng đang trượt, không ra cảm giác bồng bềnh.
         Delay ÂM: bắt đầu ngay lập tức nhưng ở giữa chu kỳ, thay vì đứng im
         chờ tới lượt. */
      .thermo .bubble--a.in-view { animation-delay: -0.0s !important; }
      .thermo .bubble--b.in-view { animation-delay: -0.5s !important; }
      .thermo .bubble--c.in-view { animation-delay: -1.0s !important; }
      .thermo .bubble--d.in-view { animation-delay: -0.25s !important; }
      .thermo .bubble--e.in-view { animation-delay: -0.75s !important; }
      .thermo .bubble--f.in-view { animation-delay: -1.25s !important; }

      .career__apps .app-1.in-view {
        animation-delay: 0s !important;
      }

      .career__apps .app-2.in-view {
        animation-delay: 0.4s !important;
      }

      /* ================================================================
         Hiệu ứng kỹ thuật tương tác vi mô (Technical Micro-Interactions)
         ================================================================ */

      /* Tabs (.tab, .rtab) - Nảy nhẹ & phát sáng shadow khi hover */
      .tab, .rtab {
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1),
                    box-shadow 0.3s ease,
                    background-color 0.3s ease,
                    color 0.3s ease !important;
      }
      .tab:hover, .rtab:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(200, 54, 90, 0.15);
      }
      .tab.is-active, .rtab.is-active {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(200, 54, 90, 0.25);
      }

      /* Cards & Boxes (.program__card, .award, .faq__item) - Nổi nhẹ khi hover (BỎ SECTION RESULTS .case) */
      .program__card,
      .award,
      .faq__item,
      .acc__item {
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1),
                    box-shadow 0.35s ease !important;
      }
      .program__card:hover,
      .award:hover {
        transform: translateY(-5px) !important;
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08) !important;
      }

      /* Loại bỏ 100% hiệu ứng hover nổi box cho Section Results (.case) */
      .case:hover {
        transform: none !important;
        box-shadow: none !important;
      }

      /* Buttons (.hero__btn, .consult__btn, .contact__btn, .ticket-btn) - Nảy nhẹ & phóng lớn khi hover */
      .hero__btn,
      .consult__btn,
      .contact__btn,
      .ticket-btn,
      .btn {
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1),
                    box-shadow 0.3s ease !important;
      }
      .hero__btn:hover,
      .consult__btn:hover,
      .contact__btn:hover,
      .ticket-btn:hover,
      .btn:hover {
        transform: translateY(-3px) scale(1.02) !important;
        box-shadow: 0 8px 22px rgba(200, 54, 90, 0.3) !important;
      }

      /* Vòng xoay liên tục cho vòng tròn vé (Ticket Circle) */
      @keyframes spinSlow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
      }
      .ticket-circle {
        animation: spinSlow 18s linear infinite !important;
        transform-origin: center center;
      }

      /* Bảng so sánh Yoyo - tô sáng dòng khi rê chuột */
      .compare tbody tr {
        transition: background-color 0.35s ease, transform 0.35s ease;
      }
      .compare tbody tr:hover {
        background-color: rgba(200, 54, 90, 0.06);
      }
    `;
    document.head.appendChild(style);
  }

  // 2. Hiệu ứng gõ chữ (Typing Effect) cho dòng chữ 19 YEARS OF EXPERIENCE
  function initTypingEffect() {
    var bandP = document.querySelector('.band p');
    if (!bandP) return;

    var fullText = bandP.textContent.trim() || '19 YEARS OF EXPERIENCE';
    bandP.style.opacity = '1';
    bandP.style.borderRight = '2px solid var(--c-primary, #c8365a)';
    bandP.style.display = 'inline-block';
    bandP.style.whiteSpace = 'nowrap';

    var charIdx = 0;
    var timer = null;

    function typeNextChar() {
      if (charIdx < fullText.length) {
        bandP.textContent = fullText.slice(0, charIdx + 1);
        charIdx++;
        timer = setTimeout(typeNextChar, 85);
      } else {
        setTimeout(function () {
          bandP.style.borderRight = 'none';
        }, 1200);
      }
    }

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          if (charIdx === 0) {
            typeNextChar();
          }
        } else {
          if (timer) clearTimeout(timer);
          charIdx = 0;
          bandP.textContent = '';
          bandP.style.borderRight = '2px solid var(--c-primary, #c8365a)';
        }
      });
    }, { threshold: 0.1 });

    observer.observe(bandP);
  }

  // 3. Lắng hệ thống quan sát IntersectionObserver (Cho các Section từ Section 2 trở đi)
  function initObserver() {
    var observerOptions = {
      root: null,
      rootMargin: '0px 0px -40px 0px',
      threshold: 0.05
    };

    var RESET_GAP = 120;

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('in-view');
          return;
        }

        if (window.scrollY <= 150) return;

        var r = entry.boundingClientRect;
        var farAbove = r.bottom < -RESET_GAP;
        var farBelow = r.top > (window.innerHeight || 0) + RESET_GAP;

        if (farAbove || farBelow) {
          entry.target.classList.remove('in-view');
        }
      });
    }, observerOptions);

    var targets = [
      // 02. Philosophy (Section 2)
      { sel: '.philo .sec-head__title', anim: 'anim-down', delay: 0 },
      { sel: '.philo .philo__lead-1', anim: 'anim-down', delay: 0.18 },
      { sel: '.philo .philo__lead-2', anim: 'anim-down', delay: 0.32 },
      { sel: '.philo__logo-mark', anim: 'anim-fade', delay: 0.25 },
      { sel: '.philo__logo-text', anim: 'anim-fade', delay: 0.35 },
      { sel: '.philo__edge--left', anim: 'anim-fade', delay: 0.4 },
      { sel: '.philo__edge--right', anim: 'anim-fade', delay: 0.4 },
      { sel: '.philo__edge--bottom', anim: 'anim-fade', delay: 0.4 },
      { sel: '.philo__item', anim: 'anim-fade', stagger: 0.18 },

      // 03. Career (Section 3)
      { sel: '.career__title', anim: 'anim-down', delay: 0 },
      { sel: '.award', anim: 'anim-up', stagger: 0.18 },
      { sel: '.career__trophy', anim: 'anim-scale', delay: 0.2 },
      { sel: '.career__confetti--l', anim: 'anim-pop-l', delay: 0.35 },
      { sel: '.career__confetti--r', anim: 'anim-pop-r', delay: 0.35 },
      { sel: '.career__live, .career__tv-title', anim: 'anim-up', stagger: 0.15 },
      { sel: '.career__player', anim: 'anim-scale', delay: 0.4 },
      { sel: '.career__apps img', anim: 'anim-bounce-icon', delay: 0.45 },

      // 04. Program (Section 4)
      { sel: '.program .sec-head__label', anim: 'anim-down', delay: 0 },
      { sel: '.program .sec-head__title', anim: 'anim-down', delay: 0.18 },
      { sel: '.tabs .tab', anim: 'anim-down', stagger: 0.12 },
      { sel: '.program__card', anim: 'anim-up', stagger: 0.2 },

      // 05. Dual (Section 5)
      { sel: '.dual .sec-head__label', anim: 'anim-down', delay: 0 },
      { sel: '.dual .sec-head__title', anim: 'anim-down', delay: 0.18 },
      { sel: '.dual__block h3', anim: 'anim-down', stagger: 0.2 },
      { sel: '.dual .check-list li', anim: 'anim-up', stagger: 0.18 },
      { sel: '.dual__devices', anim: 'anim-scale', delay: 0.25 },
      { sel: '.dual__plus', anim: 'anim-scale', delay: 0.3 },
      { sel: '.dual__therapy img', anim: 'anim-up', stagger: 0.2 },

      // 06. Ticket (Section 6)
      { sel: '.ticket__label', anim: 'anim-down', delay: 0 },
      { sel: '.ticket__sub', anim: 'anim-down', delay: 0.15 },
      { sel: '.ticket__title', anim: 'anim-down', delay: 0.25 },
      { sel: '.ticket-img', anim: 'anim-down', delay: 0.35 },
      { sel: '.ticket-btn', anim: 'anim-up', delay: 0.45 },

      // 07. Consult (Section 7)
      { sel: '.consult .sec-head__label', anim: 'anim-down', delay: 0 },
      { sel: '.consult .sec-head__title', anim: 'anim-down', delay: 0.18 },
      { sel: '.consult__row', anim: 'anim-up', stagger: 0.15 },

      // 08. Yoyo (Section 8)
      { sel: '.yoyo .sec-head__label', anim: 'anim-down', delay: 0 },
      { sel: '.yoyo .sec-head__title', anim: 'anim-down', delay: 0.18 },
      { sel: '.compare thead th', anim: 'anim-down', stagger: 0.12 },
      { sel: '.compare tbody tr', anim: 'anim-up', stagger: 0.15 },

      // 09. Features (Section 9)
      { sel: '.features .sec-head__label', anim: 'anim-down', delay: 0 },
      { sel: '.features .sec-head__title', anim: 'anim-down', delay: 0.18 },
      { sel: '.feature--01 .feature__bg', anim: 'anim-fade', delay: 0.2 },
      { sel: '.feature--01 .feature__ticket', anim: 'anim-fade', delay: 0.5 },
      { sel: '.feature__title, .feature__desc', anim: 'anim-up', stagger: 0.18 },

      // 10. Thermo (Section 10)
      { sel: '.thermo .sec-head__label', anim: 'anim-down', delay: 0 },
      { sel: '.thermo .sec-head__title', anim: 'anim-down', delay: 0.18 },
      { sel: '.thermo__chips .chip', anim: 'anim-down', stagger: 0.12 },
      { sel: '.thermo__stage', anim: 'anim-up', delay: 0.25 },
      { sel: '.thermo .bubble', anim: 'anim-bubble', stagger: 0.12 },

      // 11. Menopause (Section 11)
      { sel: '.meno .sec-head__label', anim: 'anim-down', delay: 0 },
      { sel: '.meno .sec-head__title', anim: 'anim-down', delay: 0.18 },
      { sel: '.accordion-btl .acc__item', anim: 'anim-up', stagger: 0.2 },
      { sel: '.meno__woman-pc', anim: 'anim-right', delay: 0.25 },
      { sel: '.meno__care', anim: 'anim-up', delay: 0.3 },

      // 12. Results (Section 12 - Bỏ hover nổi box cho các thẻ .case)
      { sel: '.results .sec-head__label', anim: 'anim-down', delay: 0 },
      { sel: '.results .sec-head__title', anim: 'anim-down', delay: 0.18 },
      { sel: '.results__tabs .rtab', anim: 'anim-down', stagger: 0.12 },
      { sel: '.case', anim: 'anim-up', stagger: 0.22 },

      // 13. Your Turn (Section 13)
      { sel: '.turn .sec-head__label', anim: 'anim-down', delay: 0 },
      { sel: '.turn .sec-head__title', anim: 'anim-down', delay: 0.18 },
      { sel: '.turn__dots .dot', anim: 'anim-down', stagger: 0.08 },
      { sel: '.ba-card--before', anim: 'anim-left', delay: 0.18 },
      { sel: '.ba-card--after', anim: 'anim-right', delay: 0.3 },
      { sel: '.ba-card__label', anim: 'anim-down', delay: 0.25 },
      // anim-fade chứ không phải anim-scale: badge tự canh giữa bằng
      // transform: translate(-50%…), mà mọi anim có dịch chuyển đều ghi đè
      // TOÀN BỘ transform khi vào tầm nhìn → badge bị đẩy lệch đúng nửa kích
      // thước của nó. Fade chỉ đụng opacity nên vị trí CSS được giữ nguyên.
      { sel: '.turn__badge', anim: 'anim-fade', delay: 0.35 },
      { sel: '.ba-card__react', anim: 'anim-down', stagger: 0.15 },
      { sel: '.turn__quote', anim: 'anim-up', delay: 0.3 },

      // 14. FAQ (Section 14)
      { sel: '.faq .sec-head__label', anim: 'anim-down', delay: 0 },
      { sel: '.faq .sec-head__title', anim: 'anim-down', delay: 0.18 },
      { sel: '.faq__item', anim: 'anim-up', stagger: 0.18 },

      // 15. Contact (Section 15)
      { sel: '.contact .sec-head__label', anim: 'anim-down', delay: 0 },
      { sel: '.contact .sec-head__title', anim: 'anim-down', delay: 0.18 },
      { sel: '.contact__desc', anim: 'anim-up', delay: 0.2 },
      { sel: '.contact__form .field', anim: 'anim-up', stagger: 0.16 },
      { sel: '.contact__agree', anim: 'anim-up', delay: 0.35 },
      { sel: '.contact__btn', anim: 'anim-up', delay: 0.4 },

      // 16. Footer
      { sel: '.footer__logo, .footer__info, .footer__copy', anim: 'anim-up', stagger: 0.15 }
    ];

    targets.forEach(function (t) {
      var els = document.querySelectorAll(t.sel);
      els.forEach(function (el, idx) {
        if (!el.classList.contains('btl-anim')) {
          el.classList.add('btl-anim', t.anim);
          var delay = (t.delay || 0) + (t.stagger ? idx * t.stagger : 0);
          if (delay > 0) {
            el.style.transitionDelay = delay + 's';
          }
          observer.observe(el);
        }
      });
    });
  }

  // 4. Khởi chạy
  injectAnimationStyles();

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      initObserver();
      initTypingEffect();
    });
  } else {
    initObserver();
    initTypingEffect();
  }
})();
