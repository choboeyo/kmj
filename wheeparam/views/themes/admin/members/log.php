<div class="page-header">
    <h1 class="page-title">회원 로그인 기록</h1>
</div>

<?=form_open(NULL, array("method"=>'get', 'class'=>'form-flex'))?>
<div class="form-group">
    <label class="control-label control-label-sm">기간검색</label>
    <div class="controls">
        <input class="form-control form-control-inline" name="startdate" data-toggle="datepicker" value="<?=$startdate?>">
        <input class="form-control form-control-inline" name="enddate" data-toggle="datepicker" value="<?=$enddate?>">
    </div>
</div>
<div class="form-group">
    <label class="control-label control-label-sm">검색어 입력</label>
    <div class="controls">
        <select class="form-control form-control-inline" name="sc">
            <option value="userid" <?=$sc=='userid'?'selected':''?>>아이디</option>
            <option value="nickname" <?=$sc=='nickname'?'selected':''?>>닉네임</option>
            <option value="idx" <?=$sc=='idx'?'selected':''?>>회원번호</option>
        </select>
        <input class="form-control form-control-inline" name="st" value="<?=$st?>">
    </div>
</div>
<div class="form-group">
    <label class="control-label control-label-sm"></label>
    <div class="controls">
        <button class="btn btn-default"><i class="far fa-search"></i> 필터적용</button>
    </div>
</div>
<?=form_close()?>

<div class="H10"></div>
<div data-ax5grid>
    <table>
        <thead>
        <tr>
            <th>일시</th>
            <th>아이디</th>
            <th>닉네임</th>
            <th>브라우져</th>
            <th>버젼</th>
            <th>OS</th>
            <th>모바일</th>
            <th>접속 IP</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($log_list['list'] as $row) :?>
            <tr>
                <td class="text-center"><?=$row['mlg_regtime']?></td>
                <td class="text-center"><?=$row['mem_userid']?></td>
                <td class="text-center"><?=$row['mem_nickname']?><?=display_member_menu($row['mem_idx'], "<i class='far fal fas fa-cog'></i>", $row['mem_status'])?></td>
                <td><?=$row['mlg_browser']?></td>
                <td><?=$row['mlg_version']?></td>
                <td><?=$row['mlg_platform']?></td>
                <td><?=$row['mlg_is_mobile']?></td>
                <td><?=long2ip($row['mlg_ip'])?></td>
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
</div>

<div class="H30"></div>
