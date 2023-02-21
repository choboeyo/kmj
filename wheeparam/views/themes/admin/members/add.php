<div class="W600 margin-auto">
    <div class="ax-button-group">
        <div class="left">
            <h1>신규 회원 등록</h1>
        </div>
        <div class="right">
            <button type="button" class="btn btn-default" onclick="history.back();"><i class="fal fa-chevron-left"></i> 회원목록으로</button>
        </div>
    </div>

    <?=form_open_multipart(NULL,array('autocomplete'=>'off','class'=>'form-flex'))?>
    <?=validation_errors('<p class="alert alert-danger">');?>
    <div data-ax-tbl>
        <div data-ax-tr>
            <div data-ax-td>
                <div data-ax-td-label>아이디</div>
                <div data-ax-td-wrap>
                    <input class="form-control" name="mem_userid" id="mem_userid" value="<?=set_value('mem_userid')?>" required>
                </div>
            </div>
            <div data-ax-td>
                <div data-ax-td-wrap>
                    <button type="button" class="btn btn-default" data-target="mem_userid" data-check="mem_userid" data-toggle="check-member-exist"><i class="fal fa-search"></i> 중복확인</button>
                </div>
            </div>
        </div>

        <div data-ax-tr>
            <div data-ax-td>
                <div data-ax-td-label>비밀번호</div>
                <div data-ax-td-wrap>
                    <input type="password" class="form-control form-control-inline" name="mem_password" required>
                </div>
            </div>
            <div data-ax-td>
                <div data-ax-td-label>비밀번호 확인</div>
                <div data-ax-td-wrap>
                    <input type="password" class="form-control form-control-inline" name="mem_password2" required>
                </div>
            </div>
        </div>

        <div data-ax-tr>

            <div data-ax-td>
                <div data-ax-td-label>닉네임</div>
                <div data-ax-td-wrap>
                    <input class="form-control form-control-inline" name="mem_nickname" id="mem_nickname" value="<?=set_value('mem_nickname')?>" required>
                </div>
            </div>
        </div>

        <div data-ax-tr>
            <div data-ax-td>
                <div data-ax-td-label>이메일</div>
                <div data-ax-td-wrap>
                    <input class="form-control form-control-inline" name="mem_email" data-regex="email-address" value="<?=set_value('mem_email')?>" required>
                </div>
            </div>
            <div data-ax-td>
                <div data-ax-td-wrap>
                    <?php if(USE_EMAIL_VERFY) : ?>
                        <label class="w-check"><input type="checkbox" name="mem_verfy_email" value="Y" checked><span>인증 완료</span></label>
                    <?php else :?>
                        <input type="hidden" name="mem_verfy_email" value="Y">
                    <?php endif;?>
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td>
                <div data-ax-td-label>휴대폰</div>
                <div data-ax-td-wrap>
                    <input class="form-control form-control-inline" name="mem_phone" data-regex="phone-number" value="<?=set_value('mem_phone')?>">
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td>
                <div data-ax-td-label>권한레벨</div>
                <div data-ax-td-wrap>
                    <select class="form-control form-control-inline" name="mem_auth">
                        <?php for($i=1; $i<=10; $i++):?>
                            <option value="<?=$i?>"><?=$i?></option>
                        <?php endfor;?>
                    </select>
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>성별</div>
                <div data-ax-td-wrap>
                    <label class="w-radio"><input type="radio" name="mem_gender" value="M" checked><span>남</span></label>
                    <label class="w-radio"><input type="radio" name="mem_gender" value="F"><span>여</span></label>
                    <label class="w-radio"><input type="radio" name="mem_gender" value="U"><span>미설정</span></label>
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>이메일 수신</div>
                <div data-ax-td-wrap>
                    <label class="w-radio"><input type="radio" name="mem_recv_email" value="Y"><span>수신</span></label>
                    <label class="w-radio"><input type="radio" name="mem_recv_email" value="N" checked><span>거부</span></label>
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>SMS 수신</div>
                <div data-ax-td-wrap>
                    <label class="w-radio"><input type="radio" name="mem_recv_sms" value="Y"><span>수신</span></label>
                    <label class="w-radio"><input type="radio" name="mem_recv_sms" value="N" checked><span>거부</span></label>
                </div>
            </div>
        </div>
    </div>

    <div class="H10"></div>
    <div class="text-center">
        <button class="btn btn-primary">회원 등록하기</button>
    </div>
    <?=form_close()?>
</div>