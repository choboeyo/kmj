<div class="page-header">
    <h2 class="page-title"><?=$mem['mem_nickname']?> 정보수정</h2>
</div>

<?=form_open_multipart(NULL,array('autocomplete'=>'off','class'=>'form-flex'))?>
<?=validation_errors('<p class="alert alert-danger">');?>
<input type="hidden" name="mem_idx" value="<?=$mem['mem_idx']?>">
<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>닉네임 <span class="text-danger">*</span></div>
            <div data-ax-td-wrap>
                <input class="form-control" name="mem_nickname" id="mem_nickname" value="<?=$mem['mem_nickname']?>" required>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>아이디 <span class="text-danger">*</span></div>
            <div data-ax-td-wrap>
                <input class="form-control" name="mem_userid" id="mem_userid" value="<?=$mem['mem_userid']?>" readonly>
            </div>
        </div>
    </div>

    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>이메일 <span class="text-danger">*</span></div>
            <div data-ax-td-wrap>
                <input class="form-control" name="mem_email" data-regex="email-address" value="<?=$mem['mem_email']?>" required>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>휴대폰</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="mem_phone" data-regex="phone-number" value="<?=$mem['mem_phone']?>">
            </div>
        </div>
    </div>

    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>성별</div>
            <div data-ax-td-wrap>
                <select class="form-control" name="mem_gender">
                    <option value="M" <?=$mem['mem_gender']=='M'?'selected':''?>>남</option>
                    <option value="F" <?=$mem['mem_gender']=='F'?'selected':''?>>여</option>
                    <option value="U" <?=$mem['mem_gender']=='U'?'selected':''?>>미설정</option>
                </select>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>권한레벨</div>
            <div data-ax-td-wrap>
                <select class="form-control" name="mem_auth">
                    <?php for($i=1; $i<=10; $i++):?>
                        <option value="<?=$i?>" <?=$mem['mem_auth']==$i?'selected':''?>><?=$i?></option>
                    <?php endfor;?>
                </select>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>E-mail</div>
            <div data-ax-td-wrap>
                <label class="w-radio"><input type="radio" name="mem_recv_email" value="Y" <?=$mem['mem_recv_email']=='Y'?'checked':''?>><span>수신 동의</span></label>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>SMS</div>
            <div data-ax-td-wrap>
                <label class="w-radio"><input type="radio" name="mem_recv_sms" value="Y" <?=$mem['mem_recv_sms']=='Y'?'checked':''?>><span>수신 동의</span></label>
            </div>
        </div>
    </div>
</div>

<div class="text-center MT15">
    <button class="btn btn-primary">정보 수정하기</button>
</div>
<?=form_close()?>