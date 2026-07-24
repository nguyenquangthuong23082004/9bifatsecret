<!DOCTYPE HTML>
<html lang="ko">
<head>
<title>관리자 로그인 - (주)일월세미컴</title>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="<?= base_url('admin/_common/css/import.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?= base_url('admin/_common/css/adm_login.css') ?>" type="text/css" />
<style>
    .alert {
        padding: 10px;
        background-color: #f44336;
        color: white;
        margin-bottom: 15px;
        text-align: center;
    }
</style>
</head>
<body>

<div class="bk_box">
	<p class="adm_logo"><img src="<?= base_url('admin/_images/logo.png') ?>" style="max-width: 200px;"></p>
	<dl>
		<dt>ADMIN PANEL</dt>
		<dd>Management System</dd>
	</dl>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

	<div class="log_box">
        <form action="<?= base_url('admin/loginProcess') ?>" method="post">
            <ul class="log_cont">
                <li class="left">
                    <input type="text" name="user_id" placeholder="아이디" required />
                    <span><input type="password" name="user_pw" placeholder="비밀번호" required /></span>
                </li>
                <li class="right">
                    <button type="submit" style="background: none; border: none; cursor: pointer;">
                        <p class="btn_log"><a>로그인</a></p>
                    </button>
                </li>
            </ul>
        </form>
		<p class="save"><input type="checkbox" name="save_id" value="Y" class="input_checkbox" /> 아이디 저장</p>
	</div>

	<div class="btm">
		<p class="bar"></p>
		<ul class="btm_guide">
			<li>- 관리자모드 접속화면으로 허가된 관계자만 이용 하시기 바랍니다.</li>
			<li>- <strong>COPYRIGHT ⓒ ILWOLSEMICOM ALL RIGHT RESERVED.</strong></li>
		</ul>
	</div>
</div>

</body>
</html>
