<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>닉네임</div>
            <div data-ax-td-wrap>
                <input class="form-control" value="<?=$mem['mem_nickname']?>" readonly>
            </div>
        </div>

        <div data-ax-td>
            <div data-ax-td-label>상태</div>
            <div data-ax-td-wrap>
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
                <p class="form-control-static"><?=$status?></p>
            </div>
            <div data-ax-td-wrap>
                <?php if($mem['mem_status'] == 'H') : ?>
                    <a href="#" class="btn btn-default btn-sm" onclick="APP.MEMBER.STATUS_CHANGE('<?=$mem['mem_idx']?>','<?=$mem['mem_status']?>','Y')"><i class="fal fa-user-secret"></i> 휴면 해제</a>
                <?php elseif( $mem['mem_status'] == 'Y' ) :?>
                    <a href="#" class="btn btn-default btn-sm" onclick="APP.MEMBER.STATUS_CHANGE('<?=$mem['mem_idx']?>','<?=$mem['mem_status']?>','D')"><i class="fal fa-ban"></i> 로그인 금지</a>
                <?php elseif( $mem['mem_status'] == 'D' ) :?>
                    <a href="#" class="btn btn-default btn-sm" onclick="APP.MEMBER.STATUS_CHANGE('<?=$mem['mem_idx']?>','<?=$mem['mem_status']?>','Y')"><i class="fal fa-ban"></i> 로그인 금지 해제</a>
                <?php endif;?>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>아이디</div>
            <div data-ax-td-wrap><input class="form-control" value="<?=$mem['mem_userid']?>" readonly></div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>비밀번호</div>
            <div data-ax-td-wrap>
                <a href="<?=base_url('admin/members/password/'.$mem['mem_idx'])?>" class="btn btn-default btn-sm"><i class="fal fa-lock"></i> 비밀번호 변경</a>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>E-mail</div>
            <div data-ax-td-wrap> <input class="form-control" value="<?=$mem['mem_email']?>" readonly></div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>휴대폰</div>
            <div data-ax-td-wrap>
                <input class="form-control" value="<?=$mem['mem_phone']?>" readonly>
            </div>
        </div>
    </div>
    <div data-ax-tr>

        <div data-ax-td>
            <div data-ax-td-label>성별</div>
            <div data-ax-td-wrap>
                <input class="form-control" value="<?=$mem['mem_gender']=='M'?'남':($mem['mem_gender']=='F'?'여':'미공개')?>" readonly>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>권한 레벨</div>
            <div data-ax-td-wrap>
                <p class="form-control-static"><?=$mem['mem_auth']?></p>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>E-mail</div>
            <div data-ax-td-wrap>
                <p class="form-control-static">수신 <?=$mem['mem_recv_email']=='Y'?'동의':'거부'?></p>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>SMS</div>
            <div data-ax-td-wrap>
                <p class="form-control-static">수신 <?=$mem['mem_recv_sms']=='Y'?'동의':'거부'?></p>
            </div>
        </div>
    </div>
</div>

<div class="H20"></div>
<div class="text-center">
    <a href="<?=base_url('admin/members/modify/'.$mem['mem_idx'])?>" class="btn btn-default MR10"><i class="fal fa-pencil"></i> 정보 수정</a>
    <a href="#" class="btn btn-danger" onclick="APP.MEMBER.STATUS_CHANGE('<?=$mem['mem_idx']?>','<?=$mem['mem_status']?>','N')"><i class="fal fa-user-secret"></i> 회원 탈퇴</a>
</div>