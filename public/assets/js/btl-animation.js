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
    initConsultAnimation();
    initYoyoAnimation();
    initFeaturesAnimation();
    initThermoAnimation();
    initMenoAnimation();
    initResultsAnimation();
    initTurnHeadAnimation();
    initFaqHeadAnimation();
    initContactHeadAnimation();
  }

  /**
   * 01. Hero Section Animation (Slide Down Stagger)
   * Target: .hero__label -> .hero__title -> .hero__sub
   */
  function initHeroAnimation() {
    var heroCopy = document.querySelector('.hero__copy');
    if (!heroCopy) return;

    var heroSec = document.querySelector('.hero') || heroCopy;

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
        trigger: heroSec,   // 🎯 Dùng .hero làm Trigger vì .hero__copy dùng display:contents trên mobile làm bounding-box = 0
        start: 'top 100%',
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
      observer.observe(heroSec);
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
            start: 'top 80%',
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
            start: 'top 80%',
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
            start: 'top 80%',
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

    // --- Block 4: .career__tv (Cụm TV: LIVE + Tiêu đề + Player + App Badges) ---
    var tvWrap = careerSec.querySelector('.career__tv');
    if (tvWrap) {
      var liveBadge = tvWrap.querySelector('.career__live');
      var tvTitle = tvWrap.querySelector('.career__tv-title');
      var playerEl = tvWrap.querySelector('.career__player');
      var appEls = gsap.utils.toArray(tvWrap.querySelectorAll('.career__apps img'));

      var tvHeadElements = [liveBadge, tvTitle].filter(Boolean);
      var tvBounceTweens = [];

      if (tvHeadElements.length > 0) gsap.set(tvHeadElements, { autoAlpha: 0, y: -35 });
      if (playerEl) gsap.set(playerEl, { autoAlpha: 0, y: 45 });
      if (appEls.length > 0) gsap.set(appEls, { autoAlpha: 0, y: -45 });

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

      // App Badges (app-1, app-2) trượt XUỐNG mượt mà -> sau đó nhún nhảy (bobbing loop)
      if (appEls.length > 0) {
        tvTl.to(appEls, {
          autoAlpha: 1,
          y: 0,
          duration: 1.0,
          stagger: 0.15,
          ease: 'power3.out',
          clearProps: 'will-change',
          onComplete: function () {
            appEls.forEach(function (appImg, idx) {
              var tw = gsap.to(appImg, {
                y: -10,
                duration: 1.6 + idx * 0.3,
                repeat: -1,
                yoyo: true,
                ease: 'sine.easeInOut'
              });
              tvBounceTweens.push(tw);
            });
          }
        }, '-=0.4');
      }

      function killTvBounces() {
        tvBounceTweens.forEach(function (tw) { tw.kill(); });
        tvBounceTweens = [];
      }

      function playTv() {
        killTvBounces();
        tvTl.restart();
      }

      function resetTv() {
        killTvBounces();
        tvTl.pause(0);
      }

      if (typeof ScrollTrigger !== 'undefined') {
        ScrollTrigger.create({
          trigger: tvWrap,     // 🎯 KÍCH HOẠT KHI CHẠM ĐÚNG .career__tv
          start: 'top 80%',
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
   * Kích hoạt độc lập từng khối khi cuộn chạm đúng phần tử đó:
   * - Block 1: .sec-head (Tiêu đề trượt xuống khi cuộn chạm vào .sec-head)
   * - Block 2: .tabs (Các nút Tab trượt lên khi cuộn chạm vào .tabs)
   * - Block 3: .program__card (Card hiển thị trượt lên khi cuộn chạm vào Card)
   * - Block 4: .program__desc (Mô tả chương trình trượt lên khi cuộn chạm vào Desc)
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
            trigger: programHead,
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
            trigger: tabsWrap,
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

    // --- Block 3 & 4: Từng Panel (.program__card & .program__desc) ---
    var panels = gsap.utils.toArray(programSec.querySelectorAll('.program__panel'));
    panels.forEach(function (panel) {
      var card = panel.querySelector('.program__card');
      var desc = panel.querySelector('.program__desc');

      // Block 3: .program__card (Card hiển thị trượt lên khi cuộn chạm vào Card)
      if (card) {
        gsap.set(card, { autoAlpha: 0, y: 45 });

        var cardTl = gsap.timeline({ paused: true });
        cardTl.to(card, {
          autoAlpha: 1,
          y: 0,
          duration: 1.1,
          ease: 'power3.out',
          clearProps: 'will-change'
        });

        function playCard() { cardTl.restart(); }
        function resetCard() { cardTl.pause(0); }

        if (typeof ScrollTrigger !== 'undefined') {
          ScrollTrigger.create({
            trigger: card,
            start: 'top 85%',
            onEnter: playCard,
            onEnterBack: playCard,
            onLeaveBack: resetCard
          });
        } else if ('IntersectionObserver' in window) {
          var obsCard = new IntersectionObserver(
            function (entries) {
              entries.forEach(function (entry) {
                if (entry.isIntersecting) { playCard(); }
              });
            },
            { threshold: 0.15 }
          );
          obsCard.observe(card);
        } else {
          playCard();
        }
      }

      // Block 4: .program__desc (Text mô tả trượt lên khi cuộn chạm vào Desc)
      if (desc) {
        gsap.set(desc, { autoAlpha: 0, y: 30 });

        var descTl = gsap.timeline({ paused: true });
        descTl.to(desc, {
          autoAlpha: 1,
          y: 0,
          duration: 0.9,
          ease: 'power3.out',
          clearProps: 'will-change'
        });

        function playDesc() { descTl.restart(); }
        function resetDesc() { descTl.pause(0); }

        if (typeof ScrollTrigger !== 'undefined') {
          ScrollTrigger.create({
            trigger: desc,
            start: 'top 85%',
            onEnter: playDesc,
            onEnterBack: playDesc,
            onLeaveBack: resetDesc
          });
        } else if ('IntersectionObserver' in window) {
          var obsDesc = new IntersectionObserver(
            function (entries) {
              entries.forEach(function (entry) {
                if (entry.isIntersecting) { playDesc(); }
              });
            },
            { threshold: 0.15 }
          );
          obsDesc.observe(desc);
        } else {
          playDesc();
        }
      }
    });
  }

  /**
   * 06. Dual Solution Section Animations (.sec.dual)
   * Kích hoạt độc lập từng khối khi cuộn chạm đúng phần tử đó:
   * - Block 1: .sec-head (Tiêu đề trượt xuống)
   * - Block 2: .dual__block (lần 1 - 최신기기: h3 + check-list)
   * - Block 3: .dual__devices & .dual__plus (Carousel thiết bị + dấu +)
   * - Block 4: .dual__block (lần 2 - 수기테라피: h3 + check-list)
   * - Block 5: .dual__therapy (Ảnh 수기테라피)
   */
  function initDualAnimation() {
    var dualSec = document.querySelector('.sec.dual') || document.querySelector('#dual');
    if (!dualSec) return;

    // --- Block 1: .sec-head (Tiêu đề trượt xuống) ---
    var headWrap = dualSec.querySelector('.sec-head');
    if (headWrap) {
      var headElements = [
        headWrap.querySelector('.sec-head__label'),
        headWrap.querySelector('.sec-head__title')
      ].filter(Boolean);

      if (headElements.length > 0) {
        gsap.set(headElements, { autoAlpha: 0, y: -45 });

        var headTl = gsap.timeline({ paused: true });
        headTl.to(headElements, {
          autoAlpha: 1,
          y: 0,
          duration: 1.1,
          stagger: 0.22,
          ease: 'power3.out',
          clearProps: 'will-change'
        });

        function playHead() { headTl.restart(); }
        function resetHead() { headTl.pause(0); }

        if (typeof ScrollTrigger !== 'undefined') {
          ScrollTrigger.create({
            trigger: headWrap,
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
          obsHead.observe(headWrap);
        } else {
          playHead();
        }
      }
    }

    var blocks = dualSec.querySelectorAll('.dual__block');

    // --- Block 2: .dual__block (Block 1 - 최신기기) ---
    var block1 = blocks[0];
    if (block1) {
      var block1H3 = block1.querySelector('h3');
      var block1Lis = gsap.utils.toArray(block1.querySelectorAll('.check-list li'));

      if (block1H3) gsap.set(block1H3, { autoAlpha: 0, y: -25 });
      if (block1Lis.length > 0) gsap.set(block1Lis, { autoAlpha: 0, x: -30 });

      var block1Tl = gsap.timeline({ paused: true });
      if (block1H3) {
        block1Tl.to(block1H3, {
          autoAlpha: 1,
          y: 0,
          duration: 0.9,
          ease: 'power3.out',
          clearProps: 'will-change'
        });
      }
      if (block1Lis.length > 0) {
        block1Tl.to(block1Lis, {
          autoAlpha: 1,
          x: 0,
          duration: 0.85,
          stagger: 0.15,
          ease: 'power3.out',
          clearProps: 'will-change'
        }, block1H3 ? '-=0.4' : 0);
      }

      function playBlock1() { block1Tl.restart(); }
      function resetBlock1() { block1Tl.pause(0); }

      if (typeof ScrollTrigger !== 'undefined') {
        ScrollTrigger.create({
          trigger: block1,
          start: 'top 85%',
          onEnter: playBlock1,
          onEnterBack: playBlock1,
          onLeaveBack: resetBlock1
        });
      } else if ('IntersectionObserver' in window) {
        var obsBlock1 = new IntersectionObserver(
          function (entries) {
            entries.forEach(function (entry) {
              if (entry.isIntersecting) { playBlock1(); }
            });
          },
          { threshold: 0.15 }
        );
        obsBlock1.observe(block1);
      } else {
        playBlock1();
      }
    }

    // --- Block 3: .dual__devices & .dual__plus (Thiết bị & Dấu +) ---
    var devicesWrap = dualSec.querySelector('.dual__devices');
    var plusIcon = dualSec.querySelector('.dual__plus');
    if (devicesWrap) {
      gsap.set(devicesWrap, { autoAlpha: 0, y: 35 });
      if (plusIcon) gsap.set(plusIcon, { autoAlpha: 0, scale: 0.5 });

      var devicesTl = gsap.timeline({ paused: true });
      devicesTl.to(devicesWrap, {
        autoAlpha: 1,
        y: 0,
        duration: 0.9,
        ease: 'power3.out',
        clearProps: 'will-change'
      });
      if (plusIcon) {
        devicesTl.to(plusIcon, {
          autoAlpha: 1,
          scale: 1,
          duration: 0.6,
          ease: 'back.out(1.7)',
          clearProps: 'will-change'
        }, '-=0.2');
      }

      function playDevices() { devicesTl.restart(); }
      function resetDevices() { devicesTl.pause(0); }

      if (typeof ScrollTrigger !== 'undefined') {
        ScrollTrigger.create({
          trigger: devicesWrap,
          start: 'top 85%',
          onEnter: playDevices,
          onEnterBack: playDevices,
          onLeaveBack: resetDevices
        });
      } else if ('IntersectionObserver' in window) {
        var obsDevices = new IntersectionObserver(
          function (entries) {
            entries.forEach(function (entry) {
              if (entry.isIntersecting) { playDevices(); }
            });
          },
          { threshold: 0.15 }
        );
        obsDevices.observe(devicesWrap);
      } else {
        playDevices();
      }
    }

    // --- Block 4: .dual__block (Block 2 - 수기테라피) ---
    var block2 = blocks[1];
    if (block2) {
      var block2H3 = block2.querySelector('h3');
      var block2Lis = gsap.utils.toArray(block2.querySelectorAll('.check-list li'));

      if (block2H3) gsap.set(block2H3, { autoAlpha: 0, y: -25 });
      if (block2Lis.length > 0) gsap.set(block2Lis, { autoAlpha: 0, x: -30 });

      var block2Tl = gsap.timeline({ paused: true });
      if (block2H3) {
        block2Tl.to(block2H3, {
          autoAlpha: 1,
          y: 0,
          duration: 0.9,
          ease: 'power3.out',
          clearProps: 'will-change'
        });
      }
      if (block2Lis.length > 0) {
        block2Tl.to(block2Lis, {
          autoAlpha: 1,
          x: 0,
          duration: 0.85,
          stagger: 0.15,
          ease: 'power3.out',
          clearProps: 'will-change'
        }, block2H3 ? '-=0.4' : 0);
      }

      function playBlock2() { block2Tl.restart(); }
      function resetBlock2() { block2Tl.pause(0); }

      if (typeof ScrollTrigger !== 'undefined') {
        ScrollTrigger.create({
          trigger: block2,
          start: 'top 85%',
          onEnter: playBlock2,
          onEnterBack: playBlock2,
          onLeaveBack: resetBlock2
        });
      } else if ('IntersectionObserver' in window) {
        var obsBlock2 = new IntersectionObserver(
          function (entries) {
            entries.forEach(function (entry) {
              if (entry.isIntersecting) { playBlock2(); }
            });
          },
          { threshold: 0.15 }
        );
        obsBlock2.observe(block2);
      } else {
        playBlock2();
      }
    }

    // --- Block 5: .dual__therapy (Ảnh 수기테라피) ---
    var therapyWrap = dualSec.querySelector('.dual__therapy');
    if (therapyWrap) {
      gsap.set(therapyWrap, { autoAlpha: 0, y: 35 });

      var therapyTl = gsap.timeline({ paused: true });
      therapyTl.to(therapyWrap, {
        autoAlpha: 1,
        y: 0,
        duration: 0.9,
        ease: 'power3.out',
        clearProps: 'will-change'
      });

      function playTherapy() { therapyTl.restart(); }
      function resetTherapy() { therapyTl.pause(0); }

      if (typeof ScrollTrigger !== 'undefined') {
        ScrollTrigger.create({
          trigger: therapyWrap,
          start: 'top 85%',
          onEnter: playTherapy,
          onEnterBack: playTherapy,
          onLeaveBack: resetTherapy
        });
      } else if ('IntersectionObserver' in window) {
        var obsTherapy = new IntersectionObserver(
          function (entries) {
            entries.forEach(function (entry) {
              if (entry.isIntersecting) { playTherapy(); }
            });
          },
          { threshold: 0.15 }
        );
        obsTherapy.observe(therapyWrap);
      } else {
        playTherapy();
      }
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

  /**
   * 08. Real-time Consult Status Section Animations (.sec.consult)
   * Kích hoạt độc lập từng khối khi cuộn chạm đúng phần tử đó:
   * - Block 1: .sec-head (Label & Title) trượt xuống khi cuộn chạm vào .sec-head
   * - Block 2: .consult__list các dòng .consult__row trượt lên lần lượt khi cuộn chạm vào .consult__list
   */
  function initConsultAnimation() {
    var consultSec = document.querySelector('.sec.consult') || document.querySelector('#consult');
    if (!consultSec) return;

    // --- Block 1: .sec-head (Label & Title trượt xuống) ---
    var headWrap = consultSec.querySelector('.sec-head');
    if (headWrap) {
      var headElements = [
        headWrap.querySelector('.sec-head__label'),
        headWrap.querySelector('.sec-head__title')
      ].filter(Boolean);

      if (headElements.length > 0) {
        gsap.set(headElements, { autoAlpha: 0, y: -45 });

        var headTl = gsap.timeline({ paused: true });
        headTl.to(headElements, {
          autoAlpha: 1,
          y: 0,
          duration: 1.15,
          stagger: 0.22,
          ease: 'power3.out',
          clearProps: 'will-change'
        });

        function playHead() { headTl.restart(); }
        function resetHead() { headTl.pause(0); }

        if (typeof ScrollTrigger !== 'undefined') {
          ScrollTrigger.create({
            trigger: headWrap,
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
          obsHead.observe(headWrap);
        } else {
          playHead();
        }
      }
    }

    // --- Block 2: .consult__list (Các dòng .consult__row trượt lên lần lượt) ---
    var consultList = consultSec.querySelector('.consult__list');
    var consultRows = gsap.utils.toArray(consultSec.querySelectorAll('.consult__row'));
    var triggerWrap = consultList || (consultRows.length > 0 ? consultRows[0] : null);

    if (triggerWrap && consultRows.length > 0) {
      gsap.set(consultRows, { autoAlpha: 0, y: 40 });

      var listTl = gsap.timeline({ paused: true });
      listTl.to(consultRows, {
        autoAlpha: 1,
        y: 0,
        duration: 0.95,
        stagger: 0.15,
        ease: 'power3.out',
        clearProps: 'will-change'
      });

      function playList() { listTl.restart(); }
      function resetList() { listTl.pause(0); }

      if (typeof ScrollTrigger !== 'undefined') {
        ScrollTrigger.create({
          trigger: triggerWrap,
          start: 'top 85%',
          onEnter: playList,
          onEnterBack: playList,
          onLeaveBack: resetList
        });
      } else if ('IntersectionObserver' in window) {
        var obsList = new IntersectionObserver(
          function (entries) {
            entries.forEach(function (entry) {
              if (entry.isIntersecting) { playList(); }
            });
          },
          { threshold: 0.15 }
        );
        obsList.observe(triggerWrap);
      } else {
        playList();
      }
    }
  }

  /**
   * 09. Yoyo ZERO Section Animations (.sec.yoyo & preceding .sec-head)
   * - Block 1: .sec-head (Label & Title) trượt xuống khi cuộn chạm vào .sec-head
   * - Block 2: .sec.yoyo (.compare bảng so sánh & .swipe-hint) trượt lên khi cuộn chạm vào .sec.yoyo
   */
  function initYoyoAnimation() {
    var yoyoSec = document.querySelector('.sec.yoyo') || document.querySelector('#yoyo');
    if (!yoyoSec) return;

    // Tìm sec-head ngay trước sec.yoyo hoặc sec-head có chứa nhãn Yoyo
    var yoyoHead = yoyoSec.previousElementSibling && yoyoSec.previousElementSibling.classList.contains('sec-head')
      ? yoyoSec.previousElementSibling
      : document.querySelector('.sec-head[style*="padding:74px"]');

    // --- Block 1: sec-head (Label & Title trượt xuống khi cuộn chạm đúng sec-head) ---
    if (yoyoHead) {
      var headElements = [
        yoyoHead.querySelector('.sec-head__label'),
        yoyoHead.querySelector('.sec-head__title .line1'),
        yoyoHead.querySelector('.sec-head__title .line2')
      ].filter(Boolean);

      if (headElements.length > 0) {
        gsap.set(headElements, { autoAlpha: 0, y: -45 });

        var headTl = gsap.timeline({ paused: true });
        headTl.to(headElements, {
          autoAlpha: 1,
          y: 0,
          duration: 1.15,
          stagger: 0.22,
          ease: 'power3.out',
          clearProps: 'will-change'
        });

        function playHead() { headTl.restart(); }
        function resetHead() { headTl.pause(0); }

        if (typeof ScrollTrigger !== 'undefined') {
          ScrollTrigger.create({
            trigger: yoyoHead,     // 🎯 Trigger riêng gắn vào .sec-head
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
          obsHead.observe(yoyoHead);
        } else {
          playHead();
        }
      }
    }

    // --- Block 2: .sec.yoyo (.compare bảng so sánh & .swipe-hint trượt lên) ---
    var compareWrap = yoyoSec.querySelector('.compare');
    var swipeHint = yoyoSec.querySelector('.swipe-hint');

    if (compareWrap || swipeHint) {
      if (compareWrap) gsap.set(compareWrap, { autoAlpha: 0, y: 40, scale: 0.98 });
      if (swipeHint) gsap.set(swipeHint, { autoAlpha: 0, y: 25 });

      var yoyoTl = gsap.timeline({ paused: true });

      if (compareWrap) {
        yoyoTl.to(compareWrap, {
          autoAlpha: 1,
          y: 0,
          scale: 1,
          duration: 1.1,
          ease: 'power3.out',
          clearProps: 'will-change'
        });
      }

      if (swipeHint) {
        yoyoTl.to(swipeHint, {
          autoAlpha: 1,
          y: 0,
          duration: 0.9,
          ease: 'power3.out',
          clearProps: 'will-change'
        }, compareWrap ? '-=0.4' : 0);
      }

      function playYoyo() { yoyoTl.restart(); }
      function resetYoyo() { yoyoTl.pause(0); }

      if (typeof ScrollTrigger !== 'undefined') {
        ScrollTrigger.create({
          trigger: yoyoSec,     // 🎯 Trigger riêng gắn vào .sec.yoyo
          start: 'top 85%',
          onEnter: playYoyo,
          onEnterBack: playYoyo,
          onLeaveBack: resetYoyo
        });
      } else if ('IntersectionObserver' in window) {
        var obsYoyo = new IntersectionObserver(
          function (entries) {
            entries.forEach(function (entry) {
              if (entry.isIntersecting) { playYoyo(); }
            });
          },
          { threshold: 0.15 }
        );
        obsYoyo.observe(yoyoSec);
      } else {
        playYoyo();
      }
    }
  }

  /**
   * 10. Key Features Section Animations (.sec.features)
   * Kích hoạt độc lập từng block khi cuộn chạm đúng phần tử đó:
   * - Feature 01 (.feature--01): Ảnh nền + Ticket pop-in & Tiêu đề trượt xuống
   * - Feature 02 (.feature--02): Cụm Nhiệt độ Thermal (Before/After + Badge) & Tiêu đề trượt xuống
   * - Feature 03 (.feature--03): Ảnh 1대1 PT & Tiêu đề trượt xuống
   */
  function initFeaturesAnimation() {
    var featuresSec = document.querySelector('.sec.features') || document.querySelector('#features');
    if (!featuresSec) return;

    // --- Block 1: .feature--01 ---
    var feat1 = featuresSec.querySelector('.feature--01');
    if (feat1) {
      var bgImg1 = feat1.querySelector('.feature__bg');
      var ticketImg1 = feat1.querySelector('.feature__ticket');
      var headElements1 = [
        feat1.querySelector('.sec-head__label'),
        feat1.querySelector('.sec-head__title'),
        feat1.querySelector('.feature__desc')
      ].filter(Boolean);

      if (bgImg1) gsap.set(bgImg1, { autoAlpha: 0, y: -45 });
      if (ticketImg1) gsap.set(ticketImg1, { autoAlpha: 0, y: -45 });
      if (headElements1.length > 0) gsap.set(headElements1, { autoAlpha: 0, y: -35 });

      var feat1Tl = gsap.timeline({ paused: true });

      // Bước 1: Máy chuyên dụng trượt xuống trước
      if (bgImg1) {
        feat1Tl.to(bgImg1, {
          autoAlpha: 1,
          y: 0,
          duration: 1.1,
          ease: 'power3.out',
          clearProps: 'will-change'
        });
      }

      // Bước 2: Phiếu quà tặng trượt xuống sau
      if (ticketImg1) {
        feat1Tl.to(ticketImg1, {
          autoAlpha: 1,
          y: 0,
          duration: 1.0,
          ease: 'power3.out',
          clearProps: 'will-change'
        }, bgImg1 ? '-=0.5' : 0);
      }

      // Bước 3: Tiêu đề & Mô tả trượt xuống
      if (headElements1.length > 0) {
        feat1Tl.to(headElements1, {
          autoAlpha: 1,
          y: 0,
          duration: 1.0,
          stagger: 0.2,
          ease: 'power3.out',
          clearProps: 'will-change'
        }, '-=0.4');
      }

      function play1() { feat1Tl.restart(); }
      function reset1() { feat1Tl.pause(0); }

      if (typeof ScrollTrigger !== 'undefined') {
        ScrollTrigger.create({
          trigger: feat1,       // 🎯 Trigger riêng gắn vào .feature--01
          start: 'top 85%',
          onEnter: play1,
          onEnterBack: play1,
          onLeaveBack: reset1
        });
      } else if ('IntersectionObserver' in window) {
        var obs1 = new IntersectionObserver(
          function (entries) {
            entries.forEach(function (entry) {
              if (entry.isIntersecting) { play1(); }
            });
          },
          { threshold: 0.15 }
        );
        obs1.observe(feat1);
      } else {
        play1();
      }
    }

    // --- Block 2: .feature--02 ---
    var feat2 = featuresSec.querySelector('.feature--02');
    if (feat2) {
      var thermalCols = gsap.utils.toArray(feat2.querySelectorAll('.thermal__col'));
      var thermalTags = gsap.utils.toArray(feat2.querySelectorAll('.thermal__tag'));
      var thermalBadge = feat2.querySelector('.thermal__badge');
      var headElements2 = [
        feat2.querySelector('.sec-head__label'),
        feat2.querySelector('.sec-head__title'),
        feat2.querySelector('.feature__desc')
      ].filter(Boolean);

      if (thermalCols.length > 0) gsap.set(thermalCols, { autoAlpha: 0, y: 35 });
      if (thermalTags.length > 0) gsap.set(thermalTags, { autoAlpha: 0 });
      if (thermalBadge) gsap.set(thermalBadge, { autoAlpha: 0, scale: 0.6 });
      if (headElements2.length > 0) gsap.set(headElements2, { autoAlpha: 0, y: -35 });

      var feat2Tl = gsap.timeline({ paused: true });

      if (thermalCols.length > 0) {
        feat2Tl.to(thermalCols, {
          autoAlpha: 1,
          y: 0,
          duration: 1.0,
          stagger: 0.2,
          ease: 'power3.out',
          clearProps: 'will-change'
        });
      }

      if (thermalTags.length > 0) {
        feat2Tl.to(thermalTags, {
          autoAlpha: 1,
          duration: 0.8,
          stagger: 0.15,
          ease: 'power3.out',
          clearProps: 'will-change'
        }, '-=0.4');
      }

      if (thermalBadge) {
        feat2Tl.to(thermalBadge, {
          autoAlpha: 1,
          scale: 1,
          duration: 0.85,
          ease: 'back.out(1.7)',
          clearProps: 'will-change'
        }, '-=0.4');
      }

      if (headElements2.length > 0) {
        feat2Tl.to(headElements2, {
          autoAlpha: 1,
          y: 0,
          duration: 1.0,
          stagger: 0.2,
          ease: 'power3.out',
          clearProps: 'will-change'
        }, '-=0.3');
      }

      function play2() { feat2Tl.restart(); }
      function reset2() { feat2Tl.pause(0); }

      if (typeof ScrollTrigger !== 'undefined') {
        ScrollTrigger.create({
          trigger: feat2,       // 🎯 Trigger riêng gắn vào .feature--02
          start: 'top 85%',
          onEnter: play2,
          onEnterBack: play2,
          onLeaveBack: reset2
        });
      } else if ('IntersectionObserver' in window) {
        var obs2 = new IntersectionObserver(
          function (entries) {
            entries.forEach(function (entry) {
              if (entry.isIntersecting) { play2(); }
            });
          },
          { threshold: 0.15 }
        );
        obs2.observe(feat2);
      } else {
        play2();
      }
    }

    // --- Block 3: .feature--03 ---
    var feat3 = featuresSec.querySelector('.feature--03');
    if (feat3) {
      var media3 = feat3.querySelector('.feature__media');
      var headElements3 = [
        feat3.querySelector('.sec-head__label'),
        feat3.querySelector('.sec-head__title'),
        feat3.querySelector('.feature__desc')
      ].filter(Boolean);

      if (media3) gsap.set(media3, { autoAlpha: 0, y: 40, scale: 0.96 });
      if (headElements3.length > 0) gsap.set(headElements3, { autoAlpha: 0, y: -35 });

      var feat3Tl = gsap.timeline({ paused: true });

      if (media3) {
        feat3Tl.to(media3, {
          autoAlpha: 1,
          y: 0,
          scale: 1,
          duration: 1.1,
          ease: 'power3.out',
          clearProps: 'will-change'
        });
      }

      if (headElements3.length > 0) {
        feat3Tl.to(headElements3, {
          autoAlpha: 1,
          y: 0,
          duration: 1.0,
          stagger: 0.2,
          ease: 'power3.out',
          clearProps: 'will-change'
        }, media3 ? '-=0.4' : 0);
      }

      function play3() { feat3Tl.restart(); }
      function reset3() { feat3Tl.pause(0); }

      if (typeof ScrollTrigger !== 'undefined') {
        ScrollTrigger.create({
          trigger: feat3,       // 🎯 Trigger riêng gắn vào .feature--03
          start: 'top 85%',
          onEnter: play3,
          onEnterBack: play3,
          onLeaveBack: reset3
        });
      } else if ('IntersectionObserver' in window) {
        var obs3 = new IntersectionObserver(
          function (entries) {
            entries.forEach(function (entry) {
              if (entry.isIntersecting) { play3(); }
            });
          },
          { threshold: 0.15 }
        );
        obs3.observe(feat3);
      } else {
        play3();
      }
    }
  }

  /**
   * 11. Thermo Diet Section Animations (.sec.thermo)
   * - Block 1: .sec-head & .thermo__chips trượt xuống
   * - Block 2: .thermo__player trượt lên
   * - Block 3: Bong bóng thoại (.bubble) bên trái & phải trượt xuống mượt mà.
   *            Sau khi xuất hiện xong, tự động nhún nhảy nhẹ (bobbing/bouncing loop vô hạn).
   * - Block 4: .thermo__claim trượt xuống
   */
  function initThermoAnimation() {
    var thermoSec = document.querySelector('.sec.thermo') || document.querySelector('#thermo');
    if (!thermoSec) return;

    var headWrap = thermoSec.querySelector('.thermo__head');
    var headElements = headWrap ? [
      headWrap.querySelector('.sec-head__label'),
      headWrap.querySelector('.sec-head__title')
    ].filter(Boolean) : [];

    var chipItems = gsap.utils.toArray(thermoSec.querySelectorAll('.thermo__chips .chip'));
    var playerEl = thermoSec.querySelector('.thermo__player');
    var bubbleEls = gsap.utils.toArray(thermoSec.querySelectorAll('.thermo__stage .bubble'));
    var claimElements = [
      thermoSec.querySelector('.thermo__claim h3'),
      thermoSec.querySelector('.thermo__claim p')
    ].filter(Boolean);

    // Initial state
    if (headElements.length > 0) gsap.set(headElements, { autoAlpha: 0, y: -45 });
    if (chipItems.length > 0) gsap.set(chipItems, { autoAlpha: 0, y: -35 });
    if (playerEl) gsap.set(playerEl, { autoAlpha: 0, y: 40, scale: 0.95 });
    if (bubbleEls.length > 0) gsap.set(bubbleEls, { autoAlpha: 0, y: -45 });
    if (claimElements.length > 0) gsap.set(claimElements, { autoAlpha: 0, y: 35 });

    var thermoTl = gsap.timeline({ paused: true });
    var bounceTweens = [];

    // 1. Head Elements trượt xuống
    if (headElements.length > 0) {
      thermoTl.to(headElements, {
        autoAlpha: 1,
        y: 0,
        duration: 1.1,
        stagger: 0.2,
        ease: 'power3.out',
        clearProps: 'will-change'
      });
    }

    // 2. Chips trượt xuống
    if (chipItems.length > 0) {
      thermoTl.to(chipItems, {
        autoAlpha: 1,
        y: 0,
        duration: 0.9,
        stagger: 0.15,
        ease: 'power3.out',
        clearProps: 'will-change'
      }, headElements.length > 0 ? '-=0.4' : 0);
    }

    // 3. Player trượt lên
    if (playerEl) {
      thermoTl.to(playerEl, {
        autoAlpha: 1,
        y: 0,
        scale: 1,
        duration: 1.0,
        ease: 'power3.out',
        clearProps: 'will-change'
      }, '-=0.3');
    }

    // 4. Bong bóng thoại (.bubble) trượt XUỐNG mượt mà
    if (bubbleEls.length > 0) {
      thermoTl.to(bubbleEls, {
        autoAlpha: 1,
        y: 0,
        duration: 1.0,
        stagger: 0.12,
        ease: 'power3.out',
        clearProps: 'will-change',
        onComplete: function () {
          // Sau khi tất cả đã xuất hiện xong -> Kích hoạt chuyển động nhún nhảy nhẹ (bobbing)
          bubbleEls.forEach(function (bubble, idx) {
            var tw = gsap.to(bubble, {
              y: -8,
              duration: 1.5 + (idx % 3) * 0.2,
              repeat: -1,
              yoyo: true,
              ease: 'sine.easeInOut'
            });
            bounceTweens.push(tw);
          });
        }
      }, '-=0.5');
    }

    // 5. Claim block trượt lên
    if (claimElements.length > 0) {
      thermoTl.to(claimElements, {
        autoAlpha: 1,
        y: 0,
        duration: 1.0,
        stagger: 0.2,
        ease: 'power3.out',
        clearProps: 'will-change'
      }, '-=0.3');
    }

    function killBounces() {
      bounceTweens.forEach(function (tw) { tw.kill(); });
      bounceTweens = [];
    }

    function play() {
      killBounces();
      thermoTl.restart();
    }

    function reset() {
      killBounces();
      thermoTl.pause(0);
    }

    if (typeof ScrollTrigger !== 'undefined') {
      ScrollTrigger.create({
        trigger: thermoSec,     // 🎯 Trigger gắn vào .sec.thermo
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
      observer.observe(thermoSec);
    } else {
      play();
    }
  }

  /**
   * 12. Menopause Section Animations (.sec.meno - Chỉ dành riêng cho sec-head)
   * Kích hoạt khi cuộn chạm vào đúng .sec-head của section Menopause:
   * - sec-head__label & sec-head__title trượt XUỐNG
   */
  function initMenoAnimation() {
    var menoSec = document.querySelector('.sec.meno') || document.querySelector('#menopause');
    if (!menoSec) return;

    var menoHead = menoSec.querySelector('.sec-head');
    if (!menoHead) return;

    var headElements = [
      menoHead.querySelector('.sec-head__label'),
      menoHead.querySelector('.sec-head__title .line1'),
      menoHead.querySelector('.sec-head__title .line2')
    ].filter(Boolean);

    if (headElements.length === 0) return;

    gsap.set(headElements, { autoAlpha: 0, y: -45 });

    var menoTl = gsap.timeline({ paused: true });
    menoTl.to(headElements, {
      autoAlpha: 1,
      y: 0,
      duration: 1.15,
      stagger: 0.22,
      ease: 'power3.out',
      clearProps: 'will-change'
    });

    function play() { menoTl.restart(); }
    function reset() { menoTl.pause(0); }

    if (typeof ScrollTrigger !== 'undefined') {
      ScrollTrigger.create({
        trigger: menoHead,     // 🎯 Trigger kích hoạt riêng khi chạm đúng .sec-head của Menopause
        start: 'top 85%',
        onEnter: play,
        onEnterBack: play,
        onLeaveBack: reset
      });
    } else if ('IntersectionObserver' in window) {
      var observer = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) { play(); }
          });
        },
        { threshold: 0.15 }
      );
      observer.observe(menoHead);
    } else {
      play();
    }
  }

  /**
   * 13. Results Section Animations (.sec.results - Chỉ dành riêng cho sec-head results__head)
   * Kích hoạt khi cuộn chạm vào đúng .results__head (.sec-head):
   * - sec-head__label & sec-head__title trượt XUỐNG
   */
  function initResultsAnimation() {
    var resultsSec = document.querySelector('.sec.results') || document.querySelector('#results');
    if (!resultsSec) return;

    var resultsHead = resultsSec.querySelector('.results__head') || resultsSec.querySelector('.sec-head');
    if (!resultsHead) return;

    var headElements = [
      resultsHead.querySelector('.sec-head__label'),
      resultsHead.querySelector('.sec-head__title .line1'),
      resultsHead.querySelector('.sec-head__title .line2')
    ].filter(Boolean);

    if (headElements.length === 0) return;

    gsap.set(headElements, { autoAlpha: 0, y: -45 });

    var resultsTl = gsap.timeline({ paused: true });
    resultsTl.to(headElements, {
      autoAlpha: 1,
      y: 0,
      duration: 1.15,
      stagger: 0.22,
      ease: 'power3.out',
      clearProps: 'will-change'
    });

    function play() { resultsTl.restart(); }
    function reset() { resultsTl.pause(0); }

    if (typeof ScrollTrigger !== 'undefined') {
      ScrollTrigger.create({
        trigger: resultsHead,     // 🎯 Trigger kích hoạt riêng khi chạm đúng .results__head
        start: 'top 85%',
        onEnter: play,
        onEnterBack: play,
        onLeaveBack: reset
      });
    } else if ('IntersectionObserver' in window) {
      var observer = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) { play(); }
          });
        },
        { threshold: 0.15 }
      );
      observer.observe(resultsHead);
    } else {
      play();
    }
  }

  /**
   * 14. Turn Section Animations (.sec.turn - Chỉ dành riêng cho sec-head turn__head)
   * Kích hoạt khi cuộn chạm vào đúng .turn__head (.sec-head):
   * - sec-head__label & sec-head__title trượt XUỐNG
   */
  function initTurnHeadAnimation() {
    var turnSec = document.querySelector('.sec.turn') || document.querySelector('#turn');
    if (!turnSec) return;

    var turnHead = turnSec.querySelector('.turn__head') || turnSec.querySelector('.sec-head');
    if (!turnHead) return;

    var headElements = [
      turnHead.querySelector('.sec-head__label'),
      turnHead.querySelector('.sec-head__title .line1'),
      turnHead.querySelector('.sec-head__title .line2')
    ].filter(Boolean);

    if (headElements.length === 0) return;

    gsap.set(headElements, { autoAlpha: 0, y: -45 });

    var turnTl = gsap.timeline({ paused: true });
    turnTl.to(headElements, {
      autoAlpha: 1,
      y: 0,
      duration: 1.15,
      stagger: 0.22,
      ease: 'power3.out',
      clearProps: 'will-change'
    });

    function play() { turnTl.restart(); }
    function reset() { turnTl.pause(0); }

    if (typeof ScrollTrigger !== 'undefined') {
      ScrollTrigger.create({
        trigger: turnHead,     // 🎯 Trigger kích hoạt riêng khi chạm đúng .turn__head
        start: 'top 85%',
        onEnter: play,
        onEnterBack: play,
        onLeaveBack: reset
      });
    } else if ('IntersectionObserver' in window) {
      var observer = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) { play(); }
          });
        },
        { threshold: 0.15 }
      );
      observer.observe(turnHead);
    } else {
      play();
    }
  }

  /**
   * 15. FAQ Section Animations (.sec.faq - Chỉ dành riêng cho sec-head faq__head)
   * Kích hoạt khi cuộn chạm vào đúng .faq__head (.sec-head):
   * - sec-head__label & sec-head__title trượt XUỐNG
   */
  function initFaqHeadAnimation() {
    var faqSec = document.querySelector('.sec.faq') || document.querySelector('#faq');
    if (!faqSec) return;

    var faqHead = faqSec.querySelector('.faq__head') || faqSec.querySelector('.sec-head');
    if (!faqHead) return;

    var headElements = [
      faqHead.querySelector('.sec-head__label'),
      faqHead.querySelector('.sec-head__title')
    ].filter(Boolean);

    if (headElements.length === 0) return;

    gsap.set(headElements, { autoAlpha: 0, y: -45 });

    var faqTl = gsap.timeline({ paused: true });
    faqTl.to(headElements, {
      autoAlpha: 1,
      y: 0,
      duration: 1.15,
      stagger: 0.22,
      ease: 'power3.out',
      clearProps: 'will-change'
    });

    function play() { faqTl.restart(); }
    function reset() { faqTl.pause(0); }

    if (typeof ScrollTrigger !== 'undefined') {
      ScrollTrigger.create({
        trigger: faqHead,     // 🎯 Trigger kích hoạt riêng khi chạm đúng .faq__head
        start: 'top 85%',
        onEnter: play,
        onEnterBack: play,
        onLeaveBack: reset
      });
    } else if ('IntersectionObserver' in window) {
      var observer = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) { play(); }
          });
        },
        { threshold: 0.15 }
      );
      observer.observe(faqHead);
    } else {
      play();
    }
  }

  /**
   * 16. Contact Form Section Animations (.sec.contact - Chỉ dành riêng cho sec-head contact__head)
   * Kích hoạt khi cuộn chạm vào đúng .contact__head (.sec-head):
   * - sec-head__label & sec-head__title trượt XUỐNG
   */
  function initContactHeadAnimation() {
    var contactSec = document.querySelector('.sec.contact') || document.querySelector('#contact');
    if (!contactSec) return;

    var contactHead = contactSec.querySelector('.contact__head') || contactSec.querySelector('.sec-head');
    if (!contactHead) return;

    var headElements = [
      contactHead.querySelector('.sec-head__label'),
      contactHead.querySelector('.sec-head__title .line1'),
      contactHead.querySelector('.sec-head__title .line2')
    ].filter(Boolean);

    if (headElements.length === 0) return;

    gsap.set(headElements, { autoAlpha: 0, y: -45 });

    var contactTl = gsap.timeline({ paused: true });
    contactTl.to(headElements, {
      autoAlpha: 1,
      y: 0,
      duration: 1.15,
      stagger: 0.22,
      ease: 'power3.out',
      clearProps: 'will-change'
    });

    function play() { contactTl.restart(); }
    function reset() { contactTl.pause(0); }

    if (typeof ScrollTrigger !== 'undefined') {
      ScrollTrigger.create({
        trigger: contactHead,     // 🎯 Trigger kích hoạt riêng khi chạm đúng .contact__head
        start: 'top 85%',
        onEnter: play,
        onEnterBack: play,
        onLeaveBack: reset
      });
    } else if ('IntersectionObserver' in window) {
      var observer = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) { play(); }
          });
        },
        { threshold: 0.15 }
      );
      observer.observe(contactHead);
    } else {
      play();
    }
  }

  function startGSAPSystem() {
    initGSAP();

    // Khi toàn bộ tài nguyên load xong và trình duyệt đã restore lại vị trí scroll khi F5
    window.addEventListener('load', function () {
      if (typeof ScrollTrigger !== 'undefined') {
        ScrollTrigger.refresh();
      }
    });
  }

  // Chạy khi DOM sẵn sàng
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', startGSAPSystem);
  } else {
    startGSAPSystem();
  }
})();
