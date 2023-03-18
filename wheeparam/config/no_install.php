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
        <h1 class="login-logo">휘파람 보드 미설치</h1>
        <div class="login-panel">
            <div class="login-heading">휘파람 보드가 설치되어 있지 않습니다.</div>
            <div class="login-body">
                <h4 class="text-center">현재 설정 정보</h4>
                <p class="alert alert-info text-center">현재 설정 정보가 올바르지 않을경우, config 파일을 먼저 수정해주세요</p>
                <div class="form-group">
                    <label>프로젝트 이름</label>
                    <div class="form-control"><?=PROJECT?></div>
                </div>
                <hr>
                <div class="form-group">
                    <label>DB HOST</label>
                    <div class="form-control"><?=DB_HOST?></div>
                </div>
                <div class="form-group">
                    <label>DB USER</label>
                    <div class="form-control"><?=DB_USER?></div>
                </div>
                <div class="form-group">
                    <label>DB NAME</label>
                    <div class="form-control"><?=DB_NAME?></div>
                </div>
                <a class="btn btn-block" href="install">휘파람 보드 설치 시작하기</a>
            </div>
        </div>
    </div>
</article>
</body>
</html>
