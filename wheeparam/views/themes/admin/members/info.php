<div class="ax-button-group">
    <div class="left">
        <h4><?=$mem['mem_nickname']?>님의 회원정보</h4>
    </div>
</div>

<div class="form-flex">
    <div class="form-group">
        <label class="control-label control-label-sm">아이디</label>
        <div class="controls">
            <input class="form-control" value="<?=$mem['mem_userid']?>" readonly>
        </div>
    </div>

    <?php if(! USE_EMAIL_ID) :?>
    <div class="form-group">
        <label class="control-label control-label-sm">E-mail</label>
        <div class="controls">
            <input class="form-control" value="<?=$mem['mem_email']?>" readonly>
        </div>
    </div>
    <?php endif;?>

    <div class="form-group">
        <label class="control-label control-label-sm">닉네임</label>
        <div class="controls">
            <input class="form-control" value="<?=$mem['mem_nickname']?>" readonly>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label control-label-sm">휴대폰</label>
        <div class="controls">
            <input class="form-control" value="<?=$mem['mem_phone']?>" readonly>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label control-label-sm">성별</label>
        <div class="controls">
            <input class="form-control" value="<?=$mem['mem_gender']=='M'?'남':($mem['mem_gender']=='F'?'여':'미공개')?>" readonly>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label control-label-sm">권한 레벨</label>
        <div class="controls">
            <input class="form-control" value="<?=$mem['mem_auth']?>" readonly>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label control-label-sm">E-mail 수신</label>
        <div class="controls">
            <input class="form-control" value="<?=$mem['mem_recv_email']=='Y'?'수신':'거부'?>" readonly>
        </div>
        <label class="control-label control-label-sm">SMS 수신</label>
        <div class="controls">
            <input class="form-control" value="<?=$mem['mem_recv_sms']=='Y'?'수신':'거부'?>" readonly>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label control-label-sm">상태</label>
        <div class="controls">
            <?php
            if($mem['mem_status'] == 'Y') :
                $status = '정상';
            elseif($mem['mem_status'] == 'D') :
                $status = '접근금지';
            elseif($mem['mem_status'] == 'H') :
                $status = '휴면';
            else :
                $status = '탈퇴';
            endif;
            ?>
            <input class="form-control" value="<?=$status?>" readonly>
        </div>
    </div>
</div>

<div class="H20"></div>
<div class="text-center">
    <a href="<?=base_url('admin/members/password/'.$mem['mem_idx'])?>" class="btn btn-default"><i class="far fa-lock"></i> 비밀번호 변경</a>
    <a href="<?=base_url('admin/members/modify/'.$mem['mem_idx'])?>" class="btn btn-default"><i class="far fa-pencil"></i> 정보 수정</a>
    <?php if($mem['mem_status'] == 'H') : ?>
    <a href="#" class="btn btn-warning" onclick="APP.MEMBER.STATUS_CHANGE('<?=$mem['mem_idx']?>','<?=$mem['mem_status']?>','Y')"><i class="far fa-user-secret"></i> 휴면 해제</a>
    <?php elseif( $mem['mem_status'] == 'Y' ) :?>
    <a href="#" class="btn btn-warning" onclick="APP.MEMBER.STATUS_CHANGE('<?=$mem['mem_idx']?>','<?=$mem['mem_status']?>','D')"><i class="far fa-ban"></i> 로그인 금지</a>
    <?php elseif( $mem['mem_status'] == 'D' ) :?>
    <a href="#" class="btn btn-warning" onclick="APP.MEMBER.STATUS_CHANGE('<?=$mem['mem_idx']?>','<?=$mem['mem_status']?>','Y')"><i class="far fa-ban"></i> 로그인 금지 해제</a>
    <?php endif;?>
    <a href="#" class="btn btn-danger" onclick="APP.MEMBER.STATUS_CHANGE('<?=$mem['mem_idx']?>','<?=$mem['mem_status']?>','N')"><i class="far fa-user-secret"></i> 회원 탈퇴</a>
</div>