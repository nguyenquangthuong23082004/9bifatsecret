/* =========================================================
   BITIEL Landing – GSAP Animations System
   Section-Scoped & Element-Scoped Architecture:
   01. Hero Section (.hero__copy)
   02. 19 YEARS Band (.band)
   03. Philosophy Section (.sec.philo)
   04. Career Section (.sec.career)
   05. Program Section (.sec.program)
   06. Dual Solution Section (.sec.dual)
   07. Experience Ticket Section (.sec.ticket):
       - .ticket__head: Label, Sub, Title trượt xuống
       - .ticket__visual: Tấm vé ticket-img trượt nhô lên + Vòng tròn ticket-circle bung pop-in
   ========================================================= */
(function () {
  'use strict';

  // Khởi tạo GSAP và đăng ký Plugins
  function initGSAP() {
    if (typeof gsap === 'undefined') return;

    if (typeof ScrollTrigger !== 'undefined') {
      gsap.registerPlugin(ScrollTrigger);
    }

    gsap.defaults({
      ease: 'power3.out',
      duration: 1.2
    });

    initHeroAnimation();
    initBandAnimation();
    initPhiloAnimation();
    initCareerAnimation();
    initProgramAnimation();
    initDualAnimation();
    initTicketAnimation();
  }

  /**
   * 01. Hero Section Animation (Slide Down Stagger)
   * Target: .hero__label -> .hero__title -> .hero__sub
   */
  function initHeroAnimation() {
    var heroCopy = document.querySelector('.hero__copy');
    if (!heroCopy) return;

    var heroElements = gsap.utils.toArray(
      heroCopy.querySelectorAll('.hero__label, .hero__title, .hero__sub')
    );
    if (heroElements.length === 0) return;

    gsap.set(heroElements, { autoAlpha: 0, y: -45 });

    var heroTl = gsap.timeline({ paused: true });
    heroTl.to(heroElements, {
      autoAlpha: 1,
      y: 0,
      duration: 1.3,
      stagger: 0.25,
      ease: 'power3.out',
      clearProps: 'will-change'
    });

    function play() { heroTl.restart(); }
    function reset() { heroTl.pause(0); }

    if (typeof ScrollTrigger !== 'undefined') {
      ScrollTrigger.create({
        trigger: heroCopy,
        start: 'top 95%',
        end: 'bottom top',
        onEnter: play,
        onEnterBack: play,
        onLeave: reset,
        onLeaveBack: reset
      });
    } else if ('IntersectionObserver' in window) {
      var observer = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) { play(); } else { reset(); }
          });
        },
        { threshold: 0 }
      );
      observer.observe(heroCopy);
    } else {
      play();
    }
  }

  /**
   * 02. 19 YEARS Band Typewriter Animation (.band p)
   */
  function initBandAnimation() {
    var bandEl = document.querySelector('.band');
    if (!bandEl) return;

    var pEl = bandEl.querySelector('p');
    if (!pEl) return;

    var fullText = pEl.textContent.trim();
    if (!fullText) return;

    var progressObj = { count: 0 };
    var bandTl = gsap.timeline({ paused: true });

    bandTl.to(progressObj, {
      count: fullText.length,
      duration: 1.5,
      ease: 'none',
      onUpdate: function () {
        var currentCount = Math.floor(progressObj.count);
        pEl.textContent = fullText.slice(0, currentCount);
      }
    });

    function playTypewriter() {
      pEl.textContent = '';
      bandTl.restart();
    }

    function resetTypewriter() {
      pEl.textContent = '';
      bandTl.pause(0);
    }

    if (typeof ScrollTrigger !== 'undefined') {
      ScrollTrigger.create({
        trigger: bandEl,
        start: 'top 100%',
        end: 'bottom top',
        onEnter: playTypewriter,
        onEnterBack: playTypewriter,
        onLeave: resetTypewriter,
        onLeaveBack: resetTypewriter
      });
    } else if ('IntersectionObserver' in window) {
      var observer = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) { playTypewriter(); } else { resetTypewriter(); }
          });
        },
        { threshold: 0 }
      );
      observer.observe(bandEl);
    } else {
      playTypewriter();
    }
  }

  /**
   * 03. Philosophy Section Animations (.sec.philo)
   * Phân luồng Timeline chuẩn Section-Scoped:
   * Chạy nối tiếp 1 (Head) -> 2 (Logo) -> 3 (Item). Giữ nguyên 100% khi nằm trong section.
   */
  function initPhiloAnimation() {
    var philoSec = document.querySelector('.sec.philo') || document.querySelector('#philosophy');
    if (!philoSec) return;

    var headElements = [
      philoSec.querySelector('.sec-head__title'),
      philoSec.querySelector('.philo__lead-1'),
      philoSec.querySelector('.philo__lead-2')
    ].filter(Boolean);

    var logoElements = [
      philoSec.querySelector('.philo__logo-mark'),
      philoSec.querySelector('.philo__logo-text')
    ].filter(Boolean);

    var listElements = gsap.utils.toArray(philoSec.querySelectorAll('.philo__item'));

    if (headElements.length === 0 && logoElements.length === 0 && listElements.length === 0) return;

    if (headElements.length > 0) gsap.set(headElements, { autoAlpha: 0, y: -45 });
    if (logoElements.length > 0) gsap.set(logoElements, { autoAlpha: 0, y: -40 });
    if (listElements.length > 0) gsap.set(listElements, { autoAlpha: 0, y: 45 });

    var philoTl = gsap.timeline({ paused: true });

    // Bước 1: Tiêu đề trượt xuống
    if (headElements.length > 0) {
      philoTl.to(headElements, {
        autoAlpha: 1,
        y: 0,
        duration: 1.1,
        stagger: 0.22,
        ease: 'power3.out',
        clearProps: 'will-change'
      });
    }

    // Bước 2: Logo trượt xuống
    if (logoElements.length > 0) {
      philoTl.to(logoElements, {
        autoAlpha: 1,
        y: 0,
        duration: 1.0,
        stagger: 0.18,
        ease: 'power3.out',
        clearProps: 'will-change'
      }, '-=0.4');
    }

    // Bước 3: 3 item trượt lên
    if (listElements.length > 0) {
      philoTl.to(listElements, {
        autoAlpha: 1,
        y: 0,
        duration: 1.0,
        stagger: 0.25,
        ease: 'power3.out',
        clearProps: 'will-change'
      }, '-=0.3');
    }

    function play() { philoTl.restart(); }
    function reset() { philoTl.pause(0); }

    if (typeof ScrollTrigger !== 'undefined') {
      ScrollTrigger.create({
        trigger: philoSec,
        start: 'top 80%',
        end: 'bottom top',
        onEnter: play,
        onEnterBack: play,
        onLeave: reset,
        onLeaveBack: reset
      });
    } else if ('IntersectionObserver' in window) {
      var observer = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) { play(); } else { reset(); }
          });
        },
        { threshold: 0 }
      );
      observer.observe(philoSec);
    } else {
      play();
    }
  }

  /**
   * 04. Career Section Animations (.sec.career)
   * Kích hoạt độc lập từng khối khi cuộn chạm đúng phần tử đó:
   * - Block 1: .career__title-wrap (Tiêu đề chữ trượt xuống)
   * - Block 2: .career__awards (Các thẻ .award trượt lên)
   * - Block 3: .career__trophy-wrap (.career__trophy hiện trước, sau đó tới pháo hoa)
   * - Block 4: .career__tv (Cụm TV 방송 + Player)
   */
  function initCareerAnimation() {
    var careerSec = document.querySelector('.sec.career') || document.querySelector('#career');
    if (!careerSec) return;

    // --- Block 1: .career__title-wrap (Trượt xuống cho chữ bên trong) ---
    var titleWrap = careerSec.querySelector('.career__title-wrap');
    if (titleWrap) {
      var titleElements = [
        titleWrap.querySelector('.career__arc'),
        titleWrap.querySelector('.career__title .l1'),
        titleWrap.querySelector('.career__title .l2')
      ].filter(Boolean);

      if (titleElements.length > 0) {
        gsap.set(titleElements, { autoAlpha: 0, y: -45 });

        var titleTl = gsap.timeline({ paused: true });
        titleTl.to(titleElements, {
          autoAlpha: 1,
          y: 0,
          duration: 1.2,
          stagger: 0.22,
          ease: 'power3.out',
          clearProps: 'will-change'
        });

        function playTitle() { titleTl.restart(); }
        function resetTitle() { titleTl.pause(0); }

        if (typeof ScrollTrigger !== 'undefined') {
          ScrollTrigger.create({
            trigger: titleWrap,   // 🎯 KÍCH HOẠT KHI CHẠM ĐÚNG .career__title-wrap
            start: 'top 95%',
            onEnter: playTitle,
            onEnterBack: playTitle,
            onLeaveBack: resetTitle
          });
        } else if ('IntersectionObserver' in window) {
          var obsTitle = new IntersectionObserver(
            function (entries) {
              entries.forEach(function (entry) {
                if (entry.isIntersecting) { playTitle(); }
              });
            },
            { threshold: 0.15 }
          );
          obsTitle.observe(titleWrap);
        } else {
          playTitle();
        }
      }
    }

    // --- Block 2: .career__awards (Các item class="award" trượt lên) ---
    var awardsWrap = careerSec.querySelector('.career__awards');
    if (awardsWrap) {
      var awardItems = gsap.utils.toArray(awardsWrap.querySelectorAll('.award'));
      if (awardItems.length > 0) {
        gsap.set(awardItems, { autoAlpha: 0, y: 45 });

        var awardsTl = gsap.timeline({ paused: true });
        awardsTl.to(awardItems, {
          autoAlpha: 1,
          y: 0,
          duration: 1.1,
          stagger: 0.2,
          ease: 'power3.out',
          clearProps: 'will-change'
        });

        function playAwards() { awardsTl.restart(); }
        function resetAwards() { awardsTl.pause(0); }

        if (typeof ScrollTrigger !== 'undefined') {
          ScrollTrigger.create({
            trigger: awardsWrap,  // 🎯 KÍCH HOẠT KHI CHẠM ĐÚNG .career__awards
            start: 'top 95%',
            onEnter: playAwards,
            onEnterBack: playAwards,
            onLeaveBack: resetAwards
          });
        } else if ('IntersectionObserver' in window) {
          var obsAwards = new IntersectionObserver(
            function (entries) {
              entries.forEach(function (entry) {
                if (entry.isIntersecting) { playAwards(); }
              });
            },
            { threshold: 0.15 }
          );
          obsAwards.observe(awardsWrap);
        } else {
          playAwards();
        }
      }
    }

    // --- Block 3: .career__trophy-wrap (.career__trophy hiện trước, đến pháo hoa) ---
    var trophyWrap = careerSec.querySelector('.career__trophy-wrap');
    if (trophyWrap) {
      var trophyImg = trophyWrap.querySelector('.career__trophy');
      var confettiImgs = gsap.utils.toArray(trophyWrap.querySelectorAll('.career__confetti'));

      if (trophyImg) {
        gsap.set(trophyImg, { autoAlpha: 0, y: 50, scale: 0.9 });
        if (confettiImgs.length > 0) {
          gsap.set(confettiImgs, { autoAlpha: 0, scale: 0.6 });
        }

        var trophyTl = gsap.timeline({ paused: true });

        // Bước 1: Trái Cúp trượt lên và nhô to lên
        trophyTl.to(trophyImg, {
          autoAlpha: 1,
          y: 0,
          scale: 1,
          duration: 1.15,
          ease: 'power3.out',
          clearProps: 'will-change'
        });

        // Bước 2: Pháo hoa (Confetti) bung ra 2 bên
        if (confettiImgs.length > 0) {
          trophyTl.to(confettiImgs, {
            autoAlpha: 1,
            scale: 1,
            duration: 0.9,
            stagger: 0.15,
            ease: 'back.out(1.7)',
            clearProps: 'will-change'
          }, '-=0.4');
        }

        function playTrophy() { trophyTl.restart(); }
        function resetTrophy() { trophyTl.pause(0); }

        if (typeof ScrollTrigger !== 'undefined') {
          ScrollTrigger.create({
            trigger: trophyWrap,  // 🎯 KÍCH HOẠT KHI CHẠM ĐÚNG .career__trophy-wrap
            start: 'top 95%',
            onEnter: playTrophy,
            onEnterBack: playTrophy,
            onLeaveBack: resetTrophy
          });
        } else if ('IntersectionObserver' in window) {
          var obsTrophy = new IntersectionObserver(
            function (entries) {
              entries.forEach(function (entry) {
                if (entry.isIntersecting) { playTrophy(); }
              });
            },
            { threshold: 0.15 }
          );
          obsTrophy.observe(trophyWrap);
        } else {
          playTrophy();
        }
      }
    }

    // --- Block 4: .career__tv (Cụm TV: LIVE + Tiêu đề + Player) ---
    var tvWrap = careerSec.querySelector('.career__tv');
    if (tvWrap) {
      var liveBadge = tvWrap.querySelector('.career__live');
      var tvTitle = tvWrap.querySelector('.career__tv-title');
      var playerEl = tvWrap.querySelector('.career__player');

      var tvHeadElements = [liveBadge, tvTitle].filter(Boolean);

      if (tvHeadElements.length > 0) gsap.set(tvHeadElements, { autoAlpha: 0, y: -35 });
      if (playerEl) gsap.set(playerEl, { autoAlpha: 0, y: 45 });

      var tvTl = gsap.timeline({ paused: true });

      if (tvHeadElements.length > 0) {
        tvTl.to(tvHeadElements, {
          autoAlpha: 1,
          y: 0,
          duration: 1.1,
          stagger: 0.2,
          ease: 'power3.out',
          clearProps: 'will-change'
        });
      }

      if (playerEl) {
        tvTl.to(playerEl, {
          autoAlpha: 1,
          y: 0,
          duration: 1.1,
          ease: 'power3.out',
          clearProps: 'will-change'
        }, tvHeadElements.length > 0 ? '-=0.3' : 0);
      }

      function playTv() { tvTl.restart(); }
      function resetTv() { tvTl.pause(0); }

      if (typeof ScrollTrigger !== 'undefined') {
        ScrollTrigger.create({
          trigger: tvWrap,     // 🎯 KÍCH HOẠT KHI CHẠM ĐÚNG .career__tv
          start: 'top 95%',
          onEnter: playTv,
          onEnterBack: playTv,
          onLeaveBack: resetTv
        });
      } else if ('IntersectionObserver' in window) {
        var obsTv = new IntersectionObserver(
          function (entries) {
            entries.forEach(function (entry) {
              if (entry.isIntersecting) { playTv(); }
            });
          },
          { threshold: 0.15 }
        );
        obsTv.observe(tvWrap);
      } else {
        playTv();
      }
    }
  }

  /**
   * 05. Program Section Animations (.sec.program)
   * - Block 1: .sec-head (Tiêu đề trượt xuống khi cuộn chạm vào .sec-head)
   * - Block 2: .tabs (Các nút Tab trượt lên khi cuộn chạm vào .tabs)
   * - Block 3: .program__panel (Card & Desc trượt lên khi cuộn chạm vào panel)
   */
  function initProgramAnimation() {
    var programSec = document.querySelector('.sec.program') || document.querySelector('#program');
    if (!programSec) return;

    // --- Block 1: .sec-head (Tiêu đề trượt xuống) ---
    var programHead = programSec.querySelector('.sec-head');
    if (programHead) {
      var headElements = [
        programHead.querySelector('.sec-head__label'),
        programHead.querySelector('.sec-head__title')
      ].filter(Boolean);

      if (headElements.length > 0) {
        gsap.set(headElements, { autoAlpha: 0, y: -45 });

        var headTl = gsap.timeline({ paused: true });
        headTl.to(headElements, {
          autoAlpha: 1,
          y: 0,
          duration: 1.2,
          stagger: 0.22,
          ease: 'power3.out',
          clearProps: 'will-change'
        });

        function playHead() { headTl.restart(); }
        function resetHead() { headTl.pause(0); }

        if (typeof ScrollTrigger !== 'undefined') {
          ScrollTrigger.create({
            trigger: programHead,   // 🎯 KÍCH HOẠT KHI CHẠM VÀO .sec-head CỦA PROGRAM
            start: 'top 85%',
            onEnter: playHead,
            onEnterBack: playHead,
            onLeaveBack: resetHead
          });
        } else if ('IntersectionObserver' in window) {
          var obsHead = new IntersectionObserver(
            function (entries) {
              entries.forEach(function (entry) {
                if (entry.isIntersecting) { playHead(); }
              });
            },
            { threshold: 0.15 }
          );
          obsHead.observe(programHead);
        } else {
          playHead();
        }
      }
    }

    // --- Block 2: .tabs (Các nút Tab trượt lên) ---
    var tabsWrap = programSec.querySelector('.tabs');
    if (tabsWrap) {
      var tabButtons = gsap.utils.toArray(tabsWrap.querySelectorAll('.tab'));
      if (tabButtons.length > 0) {
        gsap.set(tabButtons, { autoAlpha: 0, y: 35 });

        var tabsTl = gsap.timeline({ paused: true });
        tabsTl.to(tabButtons, {
          autoAlpha: 1,
          y: 0,
          duration: 1.0,
          stagger: 0.1,
          ease: 'power3.out',
          clearProps: 'will-change'
        });

        function playTabs() { tabsTl.restart(); }
        function resetTabs() { tabsTl.pause(0); }

        if (typeof ScrollTrigger !== 'undefined') {
          ScrollTrigger.create({
            trigger: tabsWrap,    // 🎯 KÍCH HOẠT KHI CHẠM VÀO .tabs
            start: 'top 85%',
            onEnter: playTabs,
            onEnterBack: playTabs,
            onLeaveBack: resetTabs
          });
        } else if ('IntersectionObserver' in window) {
          var obsTabs = new IntersectionObserver(
            function (entries) {
              entries.forEach(function (entry) {
                if (entry.isIntersecting) { playTabs(); }
              });
            },
            { threshold: 0.15 }
          );
          obsTabs.observe(tabsWrap);
        } else {
          playTabs();
        }
      }
    }

    // --- Block 3: Active Panel (.program__card & .program__desc) ---
    var activePanel = programSec.querySelector('.program__panel:not([hidden])');
    if (activePanel) {
      var panelElements = [
        activePanel.querySelector('.program__card'),
        activePanel.querySelector('.program__desc')
      ].filter(Boolean);

      if (panelElements.length > 0) {
        gsap.set(panelElements, { autoAlpha: 0, y: 45 });

        var panelTl = gsap.timeline({ paused: true });
        panelTl.to(panelElements, {
          autoAlpha: 1,
          y: 0,
          duration: 1.15,
          stagger: 0.22,
          ease: 'power3.out',
          clearProps: 'will-change'
        });

        function playPanel() { panelTl.restart(); }
        function resetPanel() { panelTl.pause(0); }

        if (typeof ScrollTrigger !== 'undefined') {
          ScrollTrigger.create({
            trigger: activePanel,  // 🎯 KÍCH HOẠT KHI CHẠM VÀO PANEL
            start: 'top 95%',
            onEnter: playPanel,
            onEnterBack: playPanel,
            onLeaveBack: resetPanel
          });
        } else if ('IntersectionObserver' in window) {
          var obsPanel = new IntersectionObserver(
            function (entries) {
              entries.forEach(function (entry) {
                if (entry.isIntersecting) { playPanel(); }
              });
            },
            { threshold: 0.15 }
          );
          obsPanel.observe(activePanel);
        } else {
          playPanel();
        }
      }
    }
  }

  /**
   * 06. Dual Solution Section Animations (.sec.dual)
   * - khi cuộn chạm vào sec dual:
   *   1. sec-head Tiêu đề xuất hiện với hiệu ứng trượt xuống (y: -45 -> 0)
   *   2. dual__block h3 trượt xuống + Subtext (.check-list li) trượt nhẹ lướt vào (x: -30 -> 0)
   *   3. Thẻ chứa thiết bị (.dual__devices), dấu +, thẻ 수기테라피, và ảnh 테라피 hiển thị mượt mà
   */
  function initDualAnimation() {
    var dualSec = document.querySelector('.sec.dual') || document.querySelector('#dual');
    if (!dualSec) return;

    var headElements = [
      dualSec.querySelector('.sec-head__label'),
      dualSec.querySelector('.sec-head__title')
    ].filter(Boolean);

    var blocks = dualSec.querySelectorAll('.dual__block');
    var block1 = blocks[0];
    var block2 = blocks[1];

    var block1H3 = block1 ? block1.querySelector('h3') : null;
    var block1Lis = block1 ? gsap.utils.toArray(block1.querySelectorAll('.check-list li')) : [];

    var devicesWrap = dualSec.querySelector('.dual__devices');
    var plusIcon = dualSec.querySelector('.dual__plus');

    var block2H3 = block2 ? block2.querySelector('h3') : null;
    var block2Lis = block2 ? gsap.utils.toArray(block2.querySelectorAll('.check-list li')) : [];

    var therapyWrap = dualSec.querySelector('.dual__therapy');

    // 1. Initial State Setup
    if (headElements.length > 0) gsap.set(headElements, { autoAlpha: 0, y: -45 });

    if (block1H3) gsap.set(block1H3, { autoAlpha: 0, y: -25 });
    if (block1Lis.length > 0) gsap.set(block1Lis, { autoAlpha: 0, x: -30 });

    if (devicesWrap) gsap.set(devicesWrap, { autoAlpha: 0, y: 35 });
    if (plusIcon) gsap.set(plusIcon, { autoAlpha: 0, scale: 0.5 });

    if (block2H3) gsap.set(block2H3, { autoAlpha: 0, y: -25 });
    if (block2Lis.length > 0) gsap.set(block2Lis, { autoAlpha: 0, x: -30 });

    if (therapyWrap) gsap.set(therapyWrap, { autoAlpha: 0, y: 35 });

    // 2. Timeline Definition
    var dualTl = gsap.timeline({ paused: true });

    // Bước 1: sec-head Tiêu đề trượt xuống
    if (headElements.length > 0) {
      dualTl.to(headElements, {
        autoAlpha: 1,
        y: 0,
        duration: 1.1,
        stagger: 0.22,
        ease: 'power3.out',
        clearProps: 'will-change'
      });
    }

    // Bước 2: Block 1 h3 trượt xuống + Subtext (.check-list li) trượt nhẹ lướt vào (x: -30 -> 0)
    if (block1H3) {
      dualTl.to(block1H3, {
        autoAlpha: 1,
        y: 0,
        duration: 0.9,
        ease: 'power3.out',
        clearProps: 'will-change'
      }, '-=0.3');
    }

    if (block1Lis.length > 0) {
      dualTl.to(block1Lis, {
        autoAlpha: 1,
        x: 0,
        duration: 0.85,
        stagger: 0.15,
        ease: 'power3.out',
        clearProps: 'will-change'
      }, '-=0.4');
    }

    // Bước 3: Devices Carousel + Plus Icon
    if (devicesWrap) {
      dualTl.to(devicesWrap, {
        autoAlpha: 1,
        y: 0,
        duration: 0.9,
        ease: 'power3.out',
        clearProps: 'will-change'
      }, '-=0.3');
    }

    if (plusIcon) {
      dualTl.to(plusIcon, {
        autoAlpha: 1,
        scale: 1,
        duration: 0.6,
        ease: 'back.out(1.7)',
        clearProps: 'will-change'
      }, '-=0.2');
    }

    // Bước 4: Block 2 h3 trượt xuống + Subtext (.check-list li) trượt nhẹ lướt vào (x: -30 -> 0)
    if (block2H3) {
      dualTl.to(block2H3, {
        autoAlpha: 1,
        y: 0,
        duration: 0.9,
        ease: 'power3.out',
        clearProps: 'will-change'
      }, '-=0.2');
    }

    if (block2Lis.length > 0) {
      dualTl.to(block2Lis, {
        autoAlpha: 1,
        x: 0,
        duration: 0.85,
        stagger: 0.15,
        ease: 'power3.out',
        clearProps: 'will-change'
      }, '-=0.4');
    }

    // Bước 5: Therapy Images
    if (therapyWrap) {
      dualTl.to(therapyWrap, {
        autoAlpha: 1,
        y: 0,
        duration: 0.9,
        ease: 'power3.out',
        clearProps: 'will-change'
      }, '-=0.3');
    }

    function play() { dualTl.restart(); }
    function reset() { dualTl.pause(0); }

    if (typeof ScrollTrigger !== 'undefined') {
      ScrollTrigger.create({
        trigger: dualSec,     // 🎯 Trigger gắn vào .sec.dual
        start: 'top 80%',
        end: 'bottom top',
        onEnter: play,
        onEnterBack: play,
        onLeave: reset,
        onLeaveBack: reset
      });
    } else if ('IntersectionObserver' in window) {
      var observer = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) { play(); } else { reset(); }
          });
        },
        { threshold: 0 }
      );
      observer.observe(dualSec);
    } else {
      play();
    }
  }

  /**
   * 07. Experience Ticket Section Animations (.sec.ticket)
   * - Block 1: .ticket__head (Label, Sub, Title) trượt xuống
   * - Block 2: .ticket__visual (Tấm vé ticket-img trượt nhô lên + Vòng tròn ticket-circle bung pop-in)
   */
  function initTicketAnimation() {
    var ticketSec = document.querySelector('.sec.ticket') || document.querySelector('#ticket');
    if (!ticketSec) return;

    var headWrap = ticketSec.querySelector('.ticket__head');
    var headElements = headWrap ? [
      headWrap.querySelector('.ticket__label'),
      headWrap.querySelector('.ticket__sub'),
      headWrap.querySelector('.ticket__title')
    ].filter(Boolean) : [];

    var visualWrap = ticketSec.querySelector('.ticket__visual');
    var ticketImg = visualWrap ? visualWrap.querySelector('.ticket-img') : null;
    var ticketCircle = visualWrap ? visualWrap.querySelector('.ticket-circle') : null;

    if (headElements.length === 0 && !ticketImg && !ticketCircle) return;

    if (headElements.length > 0) gsap.set(headElements, { autoAlpha: 0, y: -45 });
    if (ticketImg) gsap.set(ticketImg, { autoAlpha: 0, y: 50, scale: 0.9 });
    if (ticketCircle) gsap.set(ticketCircle, { autoAlpha: 0, scale: 0.6, rotation: -15 });

    var ticketTl = gsap.timeline({ paused: true });

    // Bước 1: Tiêu đề Ticket trượt xuống
    if (headElements.length > 0) {
      ticketTl.to(headElements, {
        autoAlpha: 1,
        y: 0,
        duration: 1.15,
        stagger: 0.22,
        ease: 'power3.out',
        clearProps: 'will-change'
      });
    }

    // Bước 2: Ảnh tấm vé ticket-img trượt nhô lên
    if (ticketImg) {
      ticketTl.to(ticketImg, {
        autoAlpha: 1,
        y: 0,
        scale: 1,
        duration: 1.1,
        ease: 'power3.out',
        clearProps: 'will-change'
      }, headElements.length > 0 ? '-=0.3' : 0);
    }

    var rotateTween = null;

    // Bước 3: Vòng tròn ticket-circle bung pop-in xoay nhẹ, sau đó quay tròn liên tục
    if (ticketCircle) {
      ticketTl.to(ticketCircle, {
        autoAlpha: 1,
        scale: 1,
        rotation: 0,
        duration: 0.9,
        ease: 'back.out(1.7)',
        clearProps: 'will-change',
        onComplete: function () {
          // Bắt đầu vòng lặp quay tròn 360 độ vô hạn liên tục
          if (!rotateTween) {
            rotateTween = gsap.to(ticketCircle, {
              rotation: 360,
              duration: 12,
              repeat: -1,
              ease: 'none'
            });
          } else {
            rotateTween.play();
          }
        }
      }, '-=0.5');
    }

    function play() {
      if (rotateTween) rotateTween.pause(0);
      ticketTl.restart();
    }

    function reset() {
      if (rotateTween) rotateTween.pause(0);
      ticketTl.pause(0);
    }

    if (typeof ScrollTrigger !== 'undefined') {
      ScrollTrigger.create({
        trigger: ticketSec,     // 🎯 Trigger gắn vào .sec.ticket
        start: 'top 80%',
        end: 'bottom top',
        onEnter: play,
        onEnterBack: play,
        onLeave: reset,
        onLeaveBack: reset
      });
    } else if ('IntersectionObserver' in window) {
      var observer = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) { play(); } else { reset(); }
          });
        },
        { threshold: 0 }
      );
      observer.observe(ticketSec);
    } else {
      play();
    }
  }

  // Chạy khi DOM sẵn sàng
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initGSAP);
  } else {
    initGSAP();
  }
})();
