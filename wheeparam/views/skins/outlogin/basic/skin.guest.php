<?php // 로그인전 상태의 스킨 ?>
<style>
    #outlogin-basic .social-login { font-size:0px; list-style:none; padding:0; margin:0; }
    #outlogin-basic .social-login > li { display:inline-block; }
</style>
<?=$form_open?>
<aside class="panel panel-default" id="outlogin-basic">
    <div class="panel-heading">
        <h4 class="panel-title"><?=langs('회원/signin')?></h4>
    </div>
    <div class="panel-body">
        <div class="login-form">
            <fieldset>
                <div class="form-group">
                    <label for="login-id" class="sr-only"><?=langs('회원/info/userid')?></label>
                    <input class="form-control" id="login-id" name="login_id" placeholder="ID" value="<?=set_value('login_id')?>" maxlength="50">
                </div>
                <div class="form-group">
                    <label for="login-pass" class="sr-only"><?=langs('회원/info/password')?></label>
                    <input type="password" class="form-control" id="login-pass" name="login_pass" placeholder="PASSWORD" value="" maxlength="20">
                </div>
                <div class="form-group">
                    <div class="pull-left">
                        <div class="checkbox" data-toggle="tooltip" title="<?=langs('회원/info/login_keep')?>">
                            <label><input type="checkbox" name="login_keep" value="Y">&nbsp;<?=langs('회원/info/login_keep')?></label>
                        </div>
                    </div>
                    <div class="pull-right">
                        <button class="btn btn-primary"><i class="far fa-check"></i>&nbsp;<?=langs('회원/signin')?></button>
                    </div>
                </div>

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
    </div>
</aside>
<?=$form_close?>