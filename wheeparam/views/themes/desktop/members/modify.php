<div class="container MT30 MB30">

    <div class="col-sm-3">
        <?=$asides_member?>
    </div>

    <div class="col-sm-9">
        <?=$form_open?>
        <article class="panel panel-default">
            <header class="panel-heading">
                <h1 class="panel-title"><?=langs('회원/info/modify')?></h1>
            </header>
            <div class="panel-body">
                <!-- START :: 필수입력값 -->
                <fieldset>
                    <legend>필수 입력정보</legend>
                    <div class="form-group">
                        <label for="userid">아이디</label>
                        <p class="form-control-static"><?=$this->member->info('userid')?></p>
                    </div>
                    <div class="form-group">
                        <label for="userpass">닉네임</label>
                        <input class="form-control" name="usernick" required value="<?=$this->member->info('nickname')?>">
                    </div>

                    <?php if( ! USE_EMAIL_ID ) : ?>
                        <!-- 이메일 아이디 미사용시 이메일 입력폼 -->
                        <div class="form-group">
                            <label for="userpass">E-mail</label>
                            <input class="form-control" name="useremail" required value="<?=$this->member->info('email')?>">
                        </div>
                    <?php endif;?>
                </fieldset>
                <!-- END :: 필수입력값 -->

                <!-- START :: 추가입력정보 -->
                <fieldset>
                    <legend>추가입력정보</legend>
                    <div class="form-group">
                        <label>연락처</label>
                        <input name="userphone" class="form-control" value="<?=$this->member->info('phone')?>">
                    </div>

                    <div class="form-group">
                        <label>성별</label>
                        <div class="radiobox">
                            <div class="radio-inline">
                                <label><input type="radio" name="usergender" value="M" checked> 비공개</label>
                            </div>
                            <div class="radio-inline">
                                <label><input type="radio" name="usergender" value="M" <?=$this->member->info('gender')=='M'?'checked':''?>> 남성</label>
                            </div>
                            <div class="radio-inline">
                                <label><input type="radio" name="usergender" value="F" <?=$this->member->info('gender')=='F'?'checked':''?>> 여성</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label><input type="checkbox" name="recv_email" value="Y" <?=$this->member->info('recv_email')=='Y'?'checked':''?>> E-mail을 수신합니다.</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="recv_sms" value="Y" <?=$this->member->info('recv_sms')=='Y'?'checked':''?>> SMS를 수신합니다.</label>
                        </div>
                    </div>
                </fieldset>
                <!-- END :: 추가입력정보 -->

                <div class="text-center">
                    <button class="btn btn-primary"><i class="fa fa-check"></i> <?=langs('회원/info/modify')?></button>
                </div>
            </div>

        </article>
        <?=$form_close?>
    </div>

</div>