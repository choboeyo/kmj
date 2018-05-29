<div class="container MT30 MB30">

    <div class="col-sm-3">
        <?=$asides_member;?>
    </div>

    <div class="col-sm-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>User Profile</h4>
            </div>
            <div class="panel-body">

                <div class="text-center MB15 MT15">
                    <?php if($this->member->info('photo')) : ?>
                        <img alt="<?=langs('회원/info/photo')?>" src="<?=base_url($this->member->info('photo'))?>" class="img-circle img-responsive" style="margin:auto">
                    <?php else :?>
                        <img alt="<?=langs('회원/info/photo')?>" src="http://placehold.it/100x100" class="img-circle img-responsive" style="margin:auto">
                    <?php endif;?>
                    <button class="btn btn-default btn-xs MT10" onclick="APP.MEMBER.POP_CHANGE_PHOTO();"><i class="fa fa-upload"></i> <?=langs('회원/info/change_photo')?></button>
                </div>

                <hr style="margin:5px 0 5px 0;">

                <!--START:: 아이디 -->
                <dl class="row">
                    <dt class="col-sm-6 text-right"><?=langs('회원/info/userid')?></dt>
                    <dd class="col-sm-6"><?=$this->member->info('userid')?></dd>
                </dl>
                <!--END:: 아이디 -->

                <!--START:: 닉네임 -->
                <dl class="row">
                    <dt class="col-sm-6 text-right"><?=langs('회원/info/nickname')?></dt>
                    <dd class="col-sm-6"><?=$this->member->info('nickname')?></dd>
                </dl>
                <!--END:: 닉네임 -->

                <!--START:: 이메일 -->
                <dl class="row">
                    <dt class="col-sm-6 text-right"><?=langs('회원/info/email')?></dt>
                    <dd class="col-sm-6"><?=$this->member->info('email')?></dd>
                </dl>
                <!--END:: 이메일 -->

                <!--START:: 연락처 -->
                <dl class="row">
                    <dt class="col-sm-6 text-right"><?=langs('회원/info/phone')?></dt>
                    <dd class="col-sm-6"><?=$this->member->info('phone')?></dd>
                </dl>
                <!--END:: 연락처 -->

                <!--START:: 성별 -->
                <dl class="row">
                    <dt class="col-sm-6 text-right"><?=langs('회원/info/gender')?></dt>
                    <dd class="col-sm-6">
                        <?php
                        if($this->member->info('gender') == "M" ) echo langs('회원/info/gender_male');
                        else if ($this->member->info('gender') == "F" )  echo langs('회원/info/gender_female');
                        else echo langs('회원/info/gender_unknown');
                        ?>
                    </dd>
                </dl>
                <!--END:: 성별 -->

                <!--START:: 포인트 -->
                <dl class="row">
                    <dt class="col-sm-6 text-right"><?=langs('회원/info/point')?></dt>
                    <dd class="col-sm-6"><?=number_format($this->member->info('point'))?></dd>
                </dl>
                <!--END:: 포인트 -->

                <!--START:: 이메일 수신여부 -->
                <dl class="row">
                    <dt class="col-sm-6 text-right"><?=langs('회원/info/recv_email')?></dt>
                    <dd class="col-sm-6"><?=$this->member->info('recv_email')?></dd>
                </dl>
                <!--END:: 이메일 수신여부 -->

                <!--START:: SMS 수신여부 -->
                <dl class="row">
                    <dt class="col-sm-6 text-right"><?=langs('회원/info/recv_sms')?></dt>
                    <dd class="col-sm-6"><?=$this->member->info('recv_sms')?></dd>
                </dl>
                <!--END:: SMS 수신여부 -->

                <!--START:: 가입일자 -->
                <dl class="row">
                    <dt class="col-sm-6 text-right"><?=langs('회원/info/regtime')?></dt>
                    <dd class="col-sm-6"><?=$this->member->info('regtime')?></dd>
                </dl>
                <!--END:: 가입일자 -->

                <!--START:: 로그인 횟수 -->
                <dl class="row">
                    <dt class="col-sm-6 text-right"><?=langs('회원/info/logcount')?></dt>
                    <dd class="col-sm-6"><?=number_format($this->member->info('logcount'))?></dd>
                </dl>
                <!--END:: 로그인 횟수 -->
            </div>
            <div class="panel-footer text-center">
                <a href="<?=base_url('members/modify')?>" class="btn btn-default"><?=langs('회원/info/modify')?></a>
                <a href="<?=base_url('members/password_change')?>" class="btn btn-default"><?=langs('회원/info/password_change')?></a>
                <a href="<?=base_url('members/withdrawals')?>" class="btn btn-danger"><?=langs('회원/info/withdrawals')?></a>
            </div>
        </div>
    </div>


</div>