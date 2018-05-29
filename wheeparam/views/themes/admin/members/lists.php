<div class="page-header">
    <h1 class="page-title">회원 목록</h1>
</div>

<?=form_open(NULL, array("method"=>'get','class'=>'form-flex','autocomplete'=>'off'))?>
<div class="form-group">
    <label class="control-label">기간 검색</label>
    <div class="controls">
        <select class="form-control form-control-inline" name="sdate">
            <option value="regtime">가입일</option>
            <option value="logtime">최근로그인</option>
        </select>
        <input class="form-control form-control-inline" name="startdate" data-toggle="datepicker" value="">
        <input class="form-control form-control-inline" name="enddate" data-toggle="datepicker" value="">
    </div>
</div>
<div class="form-group">
    <label class="control-label">권한</label>
    <div class="controls">
        <select class="form-control form-control-inline" name="mem_auth">
            <option value="">전체보기</option>
            <?php for($i=1; $i<=10; $i++) :?>
                <option value="<?=$i?>"><?=$i?></option>
            <?php endfor;?>
        </select>
    </div>
</div>

<div class="form-group">
    <label class="control-label">검색어 입력</label>
    <div class="controls">
        <select class="form-control form-control-inline" name="sc">
            <option value="mem_nickname">닉네임</option>
            <option value="mem_userid">아이디</option>
        </select>
        <input class="form-control form-control-inline" name="st" value="">
        <button class="btn btn-default btn-lg">필터적용</button>
    </div>
</div>
<?=form_close()?>

<div class="H10"></div>

<div class="ax-button-group">
    <div class="left">
        <button type="button" class="btn btn-default btn-xs"><i class="far fa-gift"></i> 선택 <?=$this->site->config('point_name')?> 지급</button>
        <button type="button" class="btn btn-default btn-xs"><i class="far fa-mobile-phone"></i> 선택 SMS 발송</button>
        <button type="button" class="btn btn-default btn-xs"><i class="far fa-envelope-o"></i> 선택 메일 발송</button>
    </div>
</div>

<div data-ax5grid>
    <table>
        <thead>
        <tr>
            <th class="W50 hidden-xs"><input type="checkbox" data-checkbox="member" data-checkbox-all="true"></th>
            <th>아이디</th>
            <th>닉네임</th>
            <th class="">E-mail</th>
            <th class="W50">상태</th>
            <th class="W50">권한</th>
            <th class="W100"><?=$this->site->config('point_name')?></th>
            <th class="W50">이메일</th>
            <th class="W50">SMS</th>
            <th class="hidden-x">소셜연동</th>
            <th class="hidden-xs W100">가입일</th>
            <th class="hidden-xs W125">가입 IP</th>
            <th class="hidden-xs W150">최근로그인</th>
            <th class="hidden-xs W125">최근로그인 IP</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($member_list['list'] as $row) :?>
        <tr>
            <td class="text-center hidden-xs"><input type="checkbox" data-checkbox="member" name="mem_idx[]" value="<?=$row['mem_idx']?>"></td>
            <td><?=$row['mem_userid']?></td>
            <td><?=$row['mem_nickname']?><?=display_member_menu($row['mem_idx'], '<i class="far fa-cog"></i>', $row['mem_status'])?></td>
            <td><?=$row['mem_email']?></td>
            <td class="text-center">
                <?php if($row['mem_status'] == 'Y') : ?>
                <label class="label label-success">정상</label>
                <?php elseif ($row['mem_status'] == 'B') :?>
                <label class="label label-danger">금지</label>
                <?php elseif ($row['mem_status'] == 'H') :?>
                <label class="label label-warning">휴면</label>
                <?php else :?>
                <label class="label label-default">탈퇴</label>
                <?php endif;?>
            </td>
            <td class="text-center"><?=$row['mem_auth']?></td>
            <td class="text-right"><?=number_format($row['mem_point'])?></td>
            <td class="text-center"><?=$row['mem_recv_email']=='Y'?'<label class="label label-success">수신</label>':'<label class="label label-default">미수신</label>'?></td>
            <td class="text-center"><?=$row['mem_recv_sms']=='Y'?'<label class="label label-success">수신</label>':'<label class="label label-default">미수신</label>'?></td>
            <td class="text-center hidden-xs">
                <?php if($row['social_naver']) : ?>
                <img src="<?=base_url('assets/images/social/icon_naver.png')?>" style="width:22px;">
                <?php endif;?>
                <?php if($row['social_facebook']) : ?>
                    <img src="<?=base_url('assets/images/social/icon_facebook.png')?>" style="width:22px;">
                <?php endif;?>
                <?php if($row['social_google']) : ?>
                    <img src="<?=base_url('assets/images/social/icon_google.png')?>" style="width:22px;">
                <?php endif;?>
                <?php if($row['social_kakao']) : ?>
                    <img src="<?=base_url('assets/images/social/icon_kakao.png')?>" style="width:22px;">
                <?php endif;?>
            </td>
            <td class="hidden-xs text-center"><?=date('Y.m.d', strtotime($row['mem_regtime']))?></td>
            <td class="hidden-xs text-center"><?=long2ip($row['mem_regip'])?></td>
            <td class="hidden-xs text-center"><?=date('Y.m.d H:i', strtotime($row['mem_logtime']))?></td>
            <td class="hidden-xs text-center"><?=long2ip($row['mem_logip'])?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<div class="H10"></div>

<div class="ax-button-group ax-button-group-bottom">
    <div class="left">
        <?=$pagination?>
    </div>
    <div class="right">
        <a href="<?=base_url('admin/members/add')?>" class="btn btn-default"><i class="far fa-plus-circle"></i> 신규 회원 등록</a>
    </div>
</div>

<div class="H30"></div>
