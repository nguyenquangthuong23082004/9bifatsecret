<?php if (!empty($popups)): ?>
<!-- Layer Popups markup -->
<?php foreach ($popups as $popup): ?>
    <?php if (($popup['P_CATE'] ?? 'L') === 'L'): ?>
    <div id="popup_layer_<?= $popup['idx'] ?>" class="layer-popup" 
         style="width: <?= !empty($popup['P_WIN_WIDTH']) ? $popup['P_WIN_WIDTH'] : '400' ?>px; 
                height: <?= !empty($popup['P_WIN_HEIGHT']) ? $popup['P_WIN_HEIGHT'] : '450' ?>px; 
                left: <?= !empty($popup['P_WIN_LEFT']) ? $popup['P_WIN_LEFT'] : '100' ?>px; 
                top: <?= !empty($popup['P_WIN_TOP']) ? $popup['P_WIN_TOP'] : '100' ?>px;">
        <div class="layer-popup__body">
            <?= $popup['P_CONTENT'] ?>
        </div>
        <div class="layer-popup__footer">
            <label class="layer-popup__label">
                <input type="checkbox" onclick="closeLayerPopupToday(<?= $popup['idx'] ?>)">
                오늘 하루 이 창을 열지 않음
            </label>
            <a onclick="closeLayerPopup(<?= $popup['idx'] ?>)" class="layer-popup__close-btn">닫기 [X]</a>
        </div>
    </div>
    <?php endif; ?>
<?php endforeach; ?>

<!-- Script to handle cookie checks and launching window popups -->
<script>
    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    function setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function closeLayerPopup(idx) {
        var el = document.getElementById('popup_layer_' + idx);
        if (el) {
            el.style.display = 'none';
        }
    }

    function closeLayerPopupToday(idx) {
        setCookie('popup_' + idx, 'done', 1);
        closeLayerPopup(idx);
    }

    // On Load: Check cookies for Layer Popups & launch Window Popups
    document.addEventListener("DOMContentLoaded", function() {
        // Check Layer Popups cookies
        <?php foreach ($popups as $popup): ?>
            <?php if (($popup['P_CATE'] ?? 'L') === 'L'): ?>
                if (getCookie('popup_<?= $popup['idx'] ?>')) {
                    closeLayerPopup(<?= $popup['idx'] ?>);
                } else {
                    var el = document.getElementById('popup_layer_<?= $popup['idx'] ?>');
                    if (el) {
                        el.style.display = 'flex';
                    }
                }
            <?php elseif (($popup['P_CATE'] ?? 'L') === 'P'): ?>
                if (!getCookie('popup_<?= $popup['idx'] ?>')) {
                    window.open(
                        '<?= base_url('popup/view/' . $popup['idx']) ?>', 
                        'popup_window_<?= $popup['idx'] ?>', 
                        'width=<?= !empty($popup['P_WIN_WIDTH']) ? $popup['P_WIN_WIDTH'] : '400' ?>,height=<?= !empty($popup['P_WIN_HEIGHT']) ? $popup['P_WIN_HEIGHT'] : '450' ?>,left=<?= !empty($popup['P_WIN_LEFT']) ? $popup['P_WIN_LEFT'] : '100' ?>,top=<?= !empty($popup['P_WIN_TOP']) ? $popup['P_WIN_TOP'] : '100' ?>'
                    );
                }
            <?php endif; ?>
        <?php endforeach; ?>
    });
</script>

<style>
/* CSS for Layer Popups */
.layer-popup {
    position: fixed;
    z-index: 9999;
    background-color: #ffffff;
    border: 1px solid #c8c8c8;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    display: none; /* Shown dynamically by JS if no cookie exists */
    flex-direction: column;
    overflow: hidden;
    border-radius: 8px;
}
.layer-popup__body {
    flex: 1;
    overflow: hidden;
    box-sizing: border-box;
    width: 100%;
    height: 100%;
}
.layer-popup__body p {
    margin: 0 !important;
    padding: 0 !important;
    width: 100% !important;
    height: 100% !important;
}
.layer-popup__body img {
    width: 100% !important;
    height: 100% !important;
    display: block;
    object-fit: fill;
}
.layer-popup__footer {
    height: 36px;
    background-color: #212529;
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 12px;
    font-size: 12px;
    user-select: none;
    box-sizing: border-box;
}
.layer-popup__close-btn {
    color: #ffffff;
    text-decoration: none;
    cursor: pointer;
    font-weight: bold;
}
.layer-popup__close-btn:hover {
    text-decoration: underline;
}
.layer-popup__label {
    display: flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
}
.layer-popup__label input {
    margin: 0;
    cursor: pointer;
}

@media (max-width: 768px) {
    /* Mobile fallback: center Layer popups and scale down to fit screen */
    .layer-popup {
        left: 5% !important;
        top: 20% !important;
        width: 90% !important;
        max-height: 60vh !important;

        left: 5% !important;
        top: 20% !important;
        width: 73% !important;
        max-height: 26vh !important;
    }
}
</style>
<?php endif; ?>
