/* =========================================================
   BTL – Nút nổi 빠른 상담 / TOP

   Nút LUÔN hiện, việc đó thuần CSS. File này chỉ lo mỗi hành vi cuộn mượt
   của nút TOP — JS hỏng hay chưa tải xong thì nút vẫn hiển thị bình thường.
   ========================================================= */
(function () {
    'use strict';

    function init() {
        var top = document.querySelector('.quickbar__top');
        var consult = document.querySelector('.quickbar__consult');

        if (top) {
            top.addEventListener('click', function () {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        if (consult) {
            consult.addEventListener('click', function (e) {
                var id = this.getAttribute('href') || '';
                var target = id.charAt(0) === '#' && id.length > 1
                    ? document.querySelector(id) : null;
                // Không tìm thấy đích thì để trình duyệt tự xử lý href như cũ.
                if (!target) return;

                e.preventDefault();

                // Header là position:sticky nên nó che mất phần đỉnh của section
                // khi cuộn tới. Trừ đúng chiều cao thật của nó tại thời điểm
                // click — chiều cao này khác nhau giữa mobile và PC.
                var gnb = document.querySelector('.gnb');
                var offset = gnb ? gnb.getBoundingClientRect().height : 0;

                window.scrollTo({
                    top: target.getBoundingClientRect().top + window.scrollY - offset,
                    behavior: 'smooth'
                });
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
