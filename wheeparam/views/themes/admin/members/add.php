<div class="ax-button-group">
    <div class="left">
        <h1>신규 회원 등록</h1>
    </div>
    <div class="right">
        <button type="button" class="btn btn-default" onclick="history.back();"><i class="far fa-chevron-left"></i> 회원목록으로</button>
    </div>
</div>

<?=form_open_multipart(NULL,array('autocomplete'=>'off','class'=>'form-flex'))?>
<?=validation_errors('<p class="alert alert-danger">');?>
<div class="panel panel-dark">
    <div class="panel-heading">
        <h4 class="panel-title">정보 입력</h4>
    </div>
    <div class="panel-body">

        <div class="form-group">
            <label class="control-label">아이디 <span class="text-danger">*</span></label>
            <div class="controls">
                <input class="form-control form-control-inline" name="mem_userid" id="mem_userid" value="<?=set_value('mem_userid')?>">
                <button type="button" class="btn btn-default btn-lg ML10" data-target="mem_userid" data-check="mem_userid" data-toggle="check-member-exist">중복확인</button>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label">비밀번호 <span class="text-danger">*</span></label>
            <div class="controls">
                <input type="password" class="form-control form-control-inline" name="mem_password">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">비밀번호 확인 <span class="text-danger">*</span></label>
            <div class="controls">
                <input type="password" class="form-control form-control-inline" name="mem_password2">
            </div>
        </div>


        <div class="form-group">
            <label class="control-label">닉네임 <span class="text-danger">*</span></label>
            <div class="controls">
                <input class="form-control form-control-inline" name="mem_nickname" id="mem_nickname" value="<?=set_value('mem_nickname')?>">
                <button type="button" class="btn btn-default btn-lg ML10" data-target="mem_nickname" data-check="mem_nickname" data-toggle="check-member-exist">중복확인</button>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label">이메일 <span class="text-danger">*</span></label>
            <div class="controls">
                <input class="form-control form-control-inline" name="mem_email" value="<?=set_value('mem_email')?>">
                <?php if(USE_EMAIL_VERFY) : ?>
                    <label><input type="checkbox" name="mem_verfy_email" value="Y" checked>인증 완료</label>
                <?php else :?>
                    <input type="hidden" name="mem_verfy_email" value="Y">
                <?php endif;?>
            </div>
        </div>

        <div class="H30"></div>

        <div class="form-group">
            <label class="control-label">휴대폰</label>
            <div class="controls">
                <input class="form-control form-control-inline" name="mem_phone" data-regex="phone" value="<?=set_value('mem_phone')?>">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label">권한레벨</label>
            <div class="controls">
                <select class="form-control form-control-inline" name="mem_auth">
                    <?php for($i=1; $i<=10; $i++):?>
                        <option value="<?=$i?>"><?=$i?></option>
                    <?php endfor;?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label">성별</label>
            <div class="controls">
                <label class="w-radio"><input type="radio" name="mem_gender" value="M" checked><span>남</span></label>
                <label class="w-radio"><input type="radio" name="mem_gender" value="F"><span>여</span></label>
                <label class="w-radio"><input type="radio" name="mem_gender" value="U"><span>미설정</span></label>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label">이메일 수신</label>
            <div class="controls">
                <label class="w-radio"><input type="radio" name="mem_recv_email" value="Y"><span>수신</span></label>
                <label class="w-radio"><input type="radio" name="mem_recv_email" value="N" checked><span>거부</span></label>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label">SMS 수신</label>
            <div class="controls">
                <label class="w-radio"><input type="radio" name="mem_recv_sms" value="Y"><span>수신</span></label>
                <label class="w-radio"><input type="radio" name="mem_recv_sms" value="N" checked><span>거부</span></label>
            </div>
        </div>
    </div>
</div>

<div class="H10"></div>
<div class="text-center">
    <button class="btn btn-lg btn-primary">회원 등록하기</button>
</div>
<?=form_close()?>
