<div class="page-header">
    <h2 class="page-title"><?=$mem['mem_nickname']?> 정보수정</h2>
</div>

<?=form_open_multipart(NULL,array('autocomplete'=>'off','class'=>'form-flex'))?>
<?=validation_errors('<p class="alert alert-danger">');?>
<input type="hidden" name="mem_idx" value="<?=$mem['mem_idx']?>">

<div class="form-group">
    <label class="control-label">아이디 <span class="text-danger">*</span></label>
    <div class="controls">
        <input class="form-control form-control-inline" name="mem_userid" id="mem_userid" value="<?=$mem['mem_userid']?>" readonly>
    </div>
</div>

<div class="form-group">
    <label class="control-label">닉네임 <span class="text-danger">*</span></label>
    <div class="controls">
        <input class="form-control form-control-inline" name="mem_nickname" id="mem_nickname" value="<?=$mem['mem_nickname']?>">
    </div>
</div>

<div class="form-group">
    <label class="control-label">이메일 <span class="text-danger">*</span></label>
    <div class="controls">
        <input class="form-control form-control-inline" name="mem_email" value="<?=$mem['mem_email']?>">
        <?php if(USE_EMAIL_VERFY) : ?>
            <label><input type="checkbox" name="mem_verfy_email" value="Y" <?=$mem['mem_verfy_email']=='Y'?'checked':''?>>인증 완료</label>
        <?php else :?>
            <input type="hidden" name="mem_verfy_email" value="Y">
        <?php endif;?>
    </div>
</div>

<div class="H30"></div>

<div class="form-group">
    <label class="control-label">휴대폰</label>
    <div class="controls">
        <input class="form-control form-control-inline" name="mem_phone" data-regex="phone" value="<?=$mem['mem_phone']?>">
    </div>
</div>

<div class="form-group">
    <label class="control-label">권한레벨</label>
    <div class="controls">
        <select class="form-control form-control-inline" name="mem_auth">
            <?php for($i=1; $i<=10; $i++):?>
                <option value="<?=$i?>" <?=$mem['mem_auth']==$i?'selected':''?>><?=$i?></option>
            <?php endfor;?>
        </select>
    </div>
</div>

<div class="form-group">
    <label class="control-label">성별</label>
    <div class="controls">
        <label class="w-radio"><input type="radio" name="mem_gender" value="M" <?=$mem['mem_gender']=='M'?'checked':''?>><span>남</span></label>
        <label class="w-radio"><input type="radio" name="mem_gender" value="F" <?=$mem['mem_gender']=='F'?'checked':''?>><span>여</span></label>
        <label class="w-radio"><input type="radio" name="mem_gender" value="U" <?=$mem['mem_gender']=='U'?'checked':''?>><span>미설정</span></label>
    </div>
</div>

<div class="form-group">
    <label class="control-label">이메일 수신</label>
    <div class="controls">
        <label class="w-radio"><input type="radio" name="mem_recv_email" value="Y" <?=$mem['mem_recv_email']=='Y'?'checked':''?>><span>수신</span></label>
        <label class="w-radio"><input type="radio" name="mem_recv_email" value="N" <?=$mem['mem_recv_email']=='N'?'checked':''?>><span>거부</span></label>
    </div>
</div>

<div class="form-group">
    <label class="control-label">SMS 수신</label>
    <div class="controls">
        <label class="w-radio"><input type="radio" name="mem_recv_sms" value="Y" <?=$mem['mem_recv_sms']=='Y'?'checked':''?>><span>수신</span></label>
        <label class="w-radio"><input type="radio" name="mem_recv_sms" value="N" <?=$mem['mem_recv_sms']=='N'?'checked':''?>><span>거부</span></label>
    </div>
</div>

<div class="H10"></div>
<div class="text-center">
    <button class="btn btn-lg btn-primary">정보 수정하기</button>
</div>
<?=form_close()?>