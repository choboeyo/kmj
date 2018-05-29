<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>휘파람 보드 초기 설치</title>
    <link rel="stylesheet" href="//spoqa.github.io/spoqa-han-sans/css/SpoqaHanSans-kr.css">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:400,100,700">
    <link rel="stylesheet" href="../assets/css/admin.min.css">
</head>
<body>
<style>
    html,body {height:100%}
</style>
<article id="login-form">
    <div class="login-wrap">
        <h1 class="login-logo">휘파람 보드 초기화</h1>
        <div class="login-panel">
            <div class="login-heading">초기화를 진행하기 위해 비밀번호를 입력하세요</div>
            <div class="login-body">
                <?=form_open(BASE_URL . "/install")?>
                <div class="form-group">
                    <label for="userpass">DB 초기화 비밀번호 입력</label>
                    <input class="form-control" type="password" name="userpass" value="" id="userpass" autofocus="true" required="required">
                </div>
                <div class="H20"></div>
                <div class="form-group">
                    <label>생성할 관리자 닉네임</label>
                    <input class="form-control" name="admin_nick" required value="휘파람">
                </div>

                <div class="form-group">
                    <label>생성할 관리자 ID</label>
                    <input class="form-control" name="admin_id" required value="admin@wheeparam.com">
                </div>

                <div class="form-group">
                    <label>생성할 관리자 비밀번호</label>
                    <input class="form-control" name="admin_pass" required>
                </div>

                <div class="form-group">
                    <label>생성할 관리자 E-mail</label>
                    <input class="form-control" name="admin_email" required value="admin@wheeparam.com">
                </div>
                <button class="btn btn-block" onclick="return confirm('DB가 초기화 됩니다. 실행하시겠습니까?');">DB초기화 실행</button>
                <?=form_close()?>
            </div>
        </div>
    </div>
</article>
</body>
</html>

