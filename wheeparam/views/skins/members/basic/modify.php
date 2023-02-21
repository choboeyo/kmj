<div class="container skin-members-basic">

    <?=$asides_member?>

    <h2 class="members-title"><?=langs('회원/info/modify')?></h2>

    <?=$form_open?>
    
    <fieldset>
        <legend class="sr-only">필수 입력정보</legend>
        <div class="members-form-group">
            <input class="members-input" id="userid" value="<?=$this->member->info('userid')?>" disabled>
            <label for="userid">아이디 <span class="required">(필수입력)</span></label>
        </div>

        <div class="members-form-group">
            <input class="members-input" name="usernick" id="usernick" required value="<?=$this->member->info('nickname')?>">
            <label for="usernick">닉네임 <span class="required">(필수입력)</span></label>
        </div>
    </fieldset>

    <fieldset>
        <legend class="sr-only">선택 입력정보</legend>
        <div class="members-form-group">
            <input class="members-input" name="usernick" id="userphone" value="<?=$this->member->info('phone')?>">
            <label for="userphone">연락처</label>
        </div>


        <label>성별</label>

        <div class="radiobox MB10">
            <label class="members-check">
                <input type="radio" name="usergender" value="U" checked>
                <span class="check-label">비공개</span>
            </label>

            <label class="members-check ML15">
                <input type="radio" name="usergender" value="M"  <?=$this->member->info('gender')=='M'?'checked':''?>>
                <span class="check-label">남성</span>
            </label>

            <label class="members-check ML15">
                <input type="radio" name="usergender" value="F"  <?=$this->member->info('gender')=='F'?'checked':''?>>
                <span class="check-label">여성</span>
            </label>
        </div>

        <label class="members-check block MB15">
            <input type="checkbox" name="recv_email" value="Y"  <?=$this->member->info('recv_email')=='Y'?'checked':''?>>
            <span class="check-label">E-mail을 수신합니다.</span>
        </label>

        <label class="members-check block MB15">
            <input type="checkbox" name="recv_sms" value="Y"  <?=$this->member->info('recv_sms')=='Y'?'checked':''?>>
            <span class="check-label">SMS를 수신합니다.</span>
        </label>

        <button class="members-btn primary"><i class="fa fa-check"></i> <?=langs('회원/info/modify')?></button>

    </fieldset>
    <?=$form_close?>

</div>