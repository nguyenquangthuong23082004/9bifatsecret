/* BITIEL landing – interactions */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {

    /* ---- GNB mobile menu ---- */
    var toggle = document.querySelector('.gnb__toggle');
    var menu = document.getElementById('gnbMenu');
    if (toggle && menu) {
      toggle.addEventListener('click', function () {
        var open = menu.hasAttribute('hidden');
        if (open) { menu.removeAttribute('hidden'); } else { menu.setAttribute('hidden', ''); }
        toggle.setAttribute('aria-expanded', String(open));
      });
      menu.querySelectorAll('a').forEach(function (a) {
        a.addEventListener('click', function () {
          menu.setAttribute('hidden', '');
          toggle.setAttribute('aria-expanded', 'false');
        });
      });
    }

    /* ---- Program tabs ---- */
    var tabs = document.querySelectorAll('.program .tab');
    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        tabs.forEach(function (t) {
          t.classList.remove('is-active');
          t.setAttribute('aria-selected', 'false');
        });
        tab.classList.add('is-active');
        tab.setAttribute('aria-selected', 'true');
        var panel = document.querySelector('.program__panel');
        var img = panel && panel.querySelector('img');
        var src = tab.getAttribute('data-img');
        if (img && src) { img.src = src; }
        var desc = tab.getAttribute('data-desc');
        var descEl = panel && panel.querySelector('.program__desc');
        if (descEl && desc) { descEl.innerHTML = desc; }
      });
    });

    /* ---- Menopause accordion (single-open) ---- */
    var accItems = document.querySelectorAll('.accordion-btl .acc__item');
    accItems.forEach(function (item) {
      var head = item.querySelector('.acc__head');
      if (!head) return;
      head.addEventListener('click', function () {
        var isOpen = item.classList.contains('is-open');
        accItems.forEach(function (i) {
          i.classList.remove('is-open');
          var h = i.querySelector('.acc__head');
          if (h) h.setAttribute('aria-expanded', 'false');
        });
        if (!isOpen) {
          item.classList.add('is-open');
          head.setAttribute('aria-expanded', 'true');
        }
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

    /* ---- Device carousel (Swiper) ---- */
    if (window.Swiper && document.querySelector('.dual__devices')) {
      new window.Swiper('.dual__devices', {
        slidesPerView: 'auto',
        centeredSlides: true,
        spaceBetween: 20,
        grabCursor: true
      });
    }

    /* ---- Your turn: before/after case carousel (Swiper) ---- */
    if (window.Swiper && document.querySelector('.turn__swiper')) {
      new window.Swiper('.turn__swiper', {
        slidesPerView: 'auto',
        centeredSlides: true,
        spaceBetween: 20,
        grabCursor: true,
        pagination: { el: '.turn__dots', clickable: true }
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
