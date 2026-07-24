<!DOCTYPE HTML>
<html lang="ko">
<head>
    <?php 
    $site_title = !empty($_settings['title']) ? $_settings['title'] : '오토스타일';
    $favicon = !empty($_settings['favico']) ? base_url('uploads/setting/' . $_settings['favico']) : base_url('favicon.ico');
    ?>
    <title><?= $site_title ?> - 관리자 로그인</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" type="image/x-icon" href="<?= $favicon ?>">
    
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="<?= base_url('adm_assets/_common/css/style_login.css') ?>" type="text/css" />
</head>
<body>

<div id="ajax_loader" class="wrap-loading display-none">
    <div>
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

<div class="login-container">
    <div class="login-card">
        <div class="text-center mb-4">
            <div class="admin-badge">
                <span class="badge-dot"></span>오토스타일 관리자
            </div>
            <h1 class="cms-title">AUTO STYLE</h1>
            <p class="cms-subtitle">관리자 전용 로그인 페이지입니다.</p>
        </div>

        <form action="<?= base_url('AdmMaster/loginProcess') ?>" method="post" name="loginForm" autocomplete="off">
            <?= csrf_field() ?>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-custom d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div><?= session()->getFlashdata('error') ?></div>
                </div>
            <?php endif; ?>

            <div class="mb-4">
                <label for="user_id" class="form-label">아이디</label>
                <div class="input-group-custom">
                    <span class="input-icon"><i class="bi bi-person"></i></span>
                    <input type="text" name="user_id" id="user_id" class="form-control" placeholder="아이디를 입력하세요" onkeyup="press_it()" required autofocus />
                </div>
            </div>

            <div class="mb-4">
                <label for="user_pw" class="form-label">비밀번호</label>
                <div class="input-group-custom">
                    <span class="input-icon"><i class="bi bi-lock"></i></span>
                    <input type="password" name="user_pw" id="user_pw" class="form-control" placeholder="비밀번호를 입력하세요" onkeyup="press_it()" required />
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check-custom">
                    <input class="form-check-input-custom" type="checkbox" name="saveId" id="saveId" value="Y">
                    <label class="form-check-label-custom" for="saveId">
                        아이디 저장
                    </label>
                </div>
            </div>

            <button type="button" onclick="loginSendit()" class="btn-login">
                로그인 <i class="bi bi-arrow-right ms-2"></i>
            </button>
        </form>

        <div class="footer-text mt-5">
            &copy; <?= date('Y') ?> AUTO STYLE. All rights reserved.
        </div>
    </div>
</div>

<script>
function loginSendit() {
    var form = document.loginForm;
    if (form.user_id.value == "" || form.user_id.value == "아이디") {
        alert("아이디를 입력해 주십시오.");
        form.user_id.focus();
        return;
    }
    if (form.user_pw.value == "" || form.user_pw.value == "비밀번호") {
        alert("비밀번호를 입력해 주십시오.");
        form.user_pw.focus();
        return;
    }

    if (form.saveId.checked) {
        saveLogin(form.user_id.value);
    } else {
        saveLogin("");
    }

    // Show loading spinner upon validation success
    document.getElementById('ajax_loader').classList.remove('display-none');
    form.submit();
}

function press_it() {
    if (window.event.keyCode == 13) {
        loginSendit();
    }
}

function setCookie(name, value, expiredays) {
    var today = new Date();
    today.setDate(today.getDate() + expiredays);
    document.cookie = name + "=" + escape(value) + "; path=/; expires=" + today.toGMTString() + ";"
}

function getCookie(key) {
    var cook = document.cookie + ";";
    var idx = cook.indexOf(key, 0);
    var val = "";
    if (idx != -1) {
        cook = cook.substring(idx, cook.length);
        begin = cook.indexOf("=", 0) + 1;
        end = cook.indexOf(";", begin);
        val = unescape(cook.substring(begin, end));
    }
    return val;
}

function saveLogin(id) {
    if (id != "") {
        setCookie("user_id", id, 70);
    } else {
        setCookie("user_id", id, -1);
    }
}

function getLogin() {
    var form = document.loginForm;
    var id = getCookie("user_id");
    if (id != "") {
        form.user_id.value = id;
        form.saveId.checked = true;
    }
}

getLogin();
</script>
</body>
</html>

