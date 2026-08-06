<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($popup['P_SUBJECT']) ?></title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100vh;
            background-color: #ffffff;
        }
        .popup-content {
            flex: 1;
            overflow: hidden;
            box-sizing: border-box;
            width: 100%;
            height: 100%;
        }
        .popup-content p {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            height: 100% !important;
        }
        .popup-content img {
            width: 100% !important;
            height: 100% !important;
            display: block;
            object-fit: fill;
        }
        .popup-footer {
            height: 40px;
            background-color: #212529;
            color: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 15px;
            font-size: 13px;
            border-top: 1px solid #dee2e6;
        }
        .popup-footer a {
            color: #ffffff;
            text-decoration: none;
            cursor: pointer;
            font-weight: 500;
        }
        .popup-footer a:hover {
            text-decoration: underline;
        }
        .popup-footer label {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            user-select: none;
        }
    </style>
</head>
<body>
    <div class="popup-content">
        <?= $popup['P_CONTENT'] ?>
    </div>
    <div class="popup-footer">
        <label>
            <input type="checkbox" id="close_today" onclick="closeToday()">
            오늘 하루 이 창을 열지 않음
        </label>
        <a onclick="window.close()">닫기 [X]</a>
    </div>

    <script>
        function setCookie(name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }

        function closeToday() {
            setCookie('popup_<?= $popup['idx'] ?>', 'done', 1);
            window.close();
        }
    </script>
</body>
</html>
