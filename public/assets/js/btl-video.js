/* =========================================================
   BTL – Video players (career / thermo)
   - Tự play khi section chứa video cuộn vào tầm nhìn
   - Tự pause khi section rời khỏi tầm nhìn
   - Ẩn/hiện nút play (svg .play / img .thermo__play) theo
     đúng trạng thái thật của <video>, không đoán bằng biến cờ
   ========================================================= */
(function () {
    'use strict';

    var SELECTOR = '.career__player, .thermo__player';
    var PLAY_BTN = '.play, .thermo__play';

    /* Trình duyệt chỉ cho autoplay khi video đang tắt tiếng. Người dùng
       bật tiếng bằng cách click vào khung player.

       Điều kiện play/pause dùng isIntersecting chứ KHÔNG dùng ngưỡng %:
       intersectionRatio là phần trăm của chính section, mà section ở đây cao
       hơn màn hình nhiều lần — ratio của nó bị chặn trên ở mức
       (chiều cao màn hình / chiều cao section), nên mọi ngưỡng kiểu 0.5 đều là
       điều kiện không bao giờ thoả. Chỉ cần section chạm mép khung hình là
       play, rời hẳn khung hình mới pause. */

    function init() {
        var players = document.querySelectorAll(SELECTOR);
        if (!players.length) return;

        players.forEach(function (player) {
            var video = player.querySelector('video');
            if (!video) return;

            var btn = player.querySelector(PLAY_BTN);
            // người dùng đã chủ động dừng -> không tự play lại khi cuộn
            var pausedByUser = false;

            video.muted = true;
            video.playsInline = true;
            video.setAttribute('playsinline', '');
            video.loop = true;

            function syncBtn() {
                if (!btn) return;
                // hiện nút khi video đang dừng hoặc đã kết thúc
                var show = video.paused || video.ended;
                btn.style.display = show ? '' : 'none';
                player.setAttribute('aria-pressed', show ? 'false' : 'true');
            }

            function play() {
                var p = video.play();
                if (p && typeof p.catch === 'function') {
                    // autoplay bị chặn -> giữ nút play để người dùng bấm
                    p.catch(syncBtn);
                }
            }

            /* --- click: toggle play/pause, đồng thời bật tiếng lần đầu --- */
            player.addEventListener('click', function () {
                if (video.paused) {
                    pausedByUser = false;
                    video.muted = false;
                    play();
                } else {
                    pausedByUser = true;
                    video.pause();
                }
            });

            player.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    player.click();
                }
            });

            /* --- nguồn sự thật duy nhất cho nút play là event của <video> --- */
            ['play', 'playing', 'pause', 'ended', 'emptied'].forEach(function (evt) {
                video.addEventListener(evt, syncBtn);
            });
            syncBtn();

            /* --- vào/ra tầm nhìn --- */
            if (!('IntersectionObserver' in window)) {
                return; // trình duyệt cũ: giữ hành vi click thủ công
            }

            var section = player.closest('section') || player;
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        if (!pausedByUser && video.paused) play();
                    } else if (!video.paused) {
                        video.pause();
                        // rời tầm nhìn là do cuộn, không phải ý người dùng
                        pausedByUser = false;
                    }
                });
            }, { threshold: 0 });

            io.observe(section);
        });

        /* Tab bị ẩn -> dừng hết cho đỡ tốn pin/băng thông */
        document.addEventListener('visibilitychange', function () {
            if (!document.hidden) return;
            document.querySelectorAll(SELECTOR + ' video').forEach(function (v) {
                if (!v.paused) v.pause();
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
