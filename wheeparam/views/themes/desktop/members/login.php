<style>
    html,body{height:100%;}
</style>
<article id="login-form">
    <div class="login-wrap">
        <div class="login">
            <header>
                <h1><?=langs('회원/signin')?></h1>
            </header>
            <?=$form_open?>
            <legend><?=langs('회원/signin')?></legend>
            <div class="login-form">
                <fieldset>
                    <div class="control-group">
                        <label for="login-id"><?=langs('회원/info/userid')?></label>
                        <input class="form-control" id="login-id" name="login_id" placeholder="ID" value="<?=set_value('login_id')?>" maxlength="50" autofocus="1">
                    </div>
                    <div class="control-group">
                        <label for="login-pass"><?=langs('회원/info/password')?></label>
                        <input type="password" class="form-control" id="login-pass" name="login_pass" placeholder="PASSWORD" value="" maxlength="20">
                    </div>
                    <div class="control-group">
                        <div class="checkbox" data-toggle="tooltip" title="<?=langs('회원/info/login_keep')?>">
                            <label><input type="checkbox" name="login_keep" value="Y">&nbsp;<?=langs('회원/info/login_keep')?></label>
                        </div>
                    </div>
                    <button class="btn btn-primary"><i class="fa fa-check"></i>&nbsp;<?=langs('회원/signin')?></button>
                </fieldset>

                <?php if(check_social_setting()) :?>
                <ul class="social-login">
                    <?php if(check_social_setting('naver')) :?>
                    <li><a href="<?=base_url('members/social-login/naver')?>"><img src="<?=base_url('assets/images/social/naver.png')?>" alt="네이버 아이디로 로그인"></a></li>
                    <?php endif;?>
                    <?php if(check_social_setting('facebook')) :?>
                    <li><a href="<?=base_url('members/social-login/facebook')?>"><img src="<?=base_url('assets/images/social/facebook.png')?>" alt="페이스북 아이디로 로그인"></a></li>
                    <?php endif;?>
                    <?php if(check_social_setting('kakao')) :?>
                    <li><a href="<?=base_url('members/social-login/kakao')?>"><img src="<?=base_url('assets/images/social/kakao.png')?>" alt="카카오 아이디로 로그인"></a></li>
                    <?php endif;?>
                    <?php if(check_social_setting('google')) :?>
                    <li><a href="<?=base_url('members/social-login/google')?>"><img src="<?=base_url('assets/images/social/google.png')?>" alt="카카오 아이디로 로그인"><</a></li>
                    <?php endif;?>
                </ul>
                <?php endif;?>

            </div>
            <?=$form_close?>
        </div>
    </div>
</article>