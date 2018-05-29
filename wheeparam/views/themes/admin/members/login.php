<style>
    html,body,.frame-content {height:100%;}
</style>
<article id="login-form">
    <div class="login-wrap">

        <h1 class="login-logo">ADMINISTRATOR</h1>
        <div class="login-panel">
            <div class="login-heading">로그인이 필요한 페이지 입니다.</div>
            <div class="login-body">
                <?=$form_open?>
                <div class="form-group">
                    <label for="login-id"><i class="far fal fas fa-pen-square"></i> ID</label>
                    <input class="form-control" id="login-id" name="login_id" placeholder="ID" value="<?=set_value('login_id')?>" maxlength="50" autofocus="1">
                </div>
                <div class="form-group">
                    <label for="login-pass"><i class="far fal fas fa-key"></i> PASSWORD</label>
                    <input type="password" class="form-control" id="login-pass" name="login_pass" placeholder="PASSWORD" value="" maxlength="20">
                </div>
                <button class="btn btn-block">LOGIN</button>
                <?=$form_close?>
            </div>
        </div>
        <p class="login-notice"><i class="far fal fas fa-exclamation-circle"></i> 관리자모드는 IE9 이하의 브라우져를 지원하지 않습니다.</p>
    </div>
</article>