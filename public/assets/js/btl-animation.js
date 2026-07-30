/* =========================================================
   BITIEL Landing – GSAP Animations System
   Built according to Official GSAP Best Practices & Performance
   ========================================================= */
(function () {
  'use strict';

  // Khởi tạo GSAP và đăng ký Plugins
  function initGSAP() {
    if (typeof gsap === 'undefined') return;

    if (typeof ScrollTrigger !== 'undefined') {
      gsap.registerPlugin(ScrollTrigger);
    }

    // Thiết lập cấu hình mặc định GSAP (Thời gian trượt chậm rãi, mượt mà)
    gsap.defaults({
      ease: 'power3.out',
      duration: 1.35
    });

    // Kích hoạt các module animation
    initHeroAnimation();
  }

  /**
   * 01. Hero Section Animation (Fade Up Stagger)
   * Target: .hero__label -> .hero__title -> .hero__sub
   */
  function initHeroAnimation() {
    var heroCopy = document.querySelector('.hero__copy');
    if (!heroCopy) return;

    var heroElements = gsap.utils.toArray(
      heroCopy.querySelectorAll('.hero__label, .hero__title, .hero__sub')
    );

    if (heroElements.length === 0) return;

    // Timeline ở trạng thái tạm dừng
    var heroTl = gsap.timeline({ paused: true });

    // GSAP Performance Best Practice: dùng autoAlpha thay cho opacity thuần
    heroTl.fromTo(
      heroElements,
      {
        autoAlpha: 0,
        y: 45,
        force3D: true
      },
      {
        autoAlpha: 1,
        y: 0,
        duration: 1.35,
        stagger: 0.28,
        ease: 'power3.out',
        clearProps: 'will-change'
      }
    );


    // Kích hoạt bằng ScrollTrigger nếu có, hoặc dùng IntersectionObserver làm fallback
    if (typeof ScrollTrigger !== 'undefined') {
      ScrollTrigger.create({
        trigger: heroCopy,
        start: 'top 85%',
        onEnter: function () { heroTl.restart(); },
        onEnterBack: function () { heroTl.restart(); },
        onLeave: function () { heroTl.pause(0); },
        onLeaveBack: function () { heroTl.pause(0); }
      });
    } else if ('IntersectionObserver' in window) {
      var observer = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              heroTl.restart();
            } else {
              heroTl.pause(0);
            }
          });
        },
        { threshold: 0.15 }
      );
      observer.observe(heroCopy);
    } else {
      heroTl.play();
    }
  }

  // Chạy khi DOM sẵn sàng
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initGSAP);
  } else {
    initGSAP();
  }
})();
