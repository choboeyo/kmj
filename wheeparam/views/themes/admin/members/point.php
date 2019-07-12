<div class="page-header">
<h1 class="page-title"><?=$mem['mem_nickname']?>님의 <?=$this->site->config('point_name')?> 정보</h1>
</div>

<?=form_open(NULL, array('method'=>'get', 'class'=>'form-flex','autocomplete'=>'off'))?>
<div class="form-group">
    <label class="control-label control-label-sm"><?=$this->site->config('point_name')?> 유형</label>
    <div class="controls">
        <select class="form-control form-control-inline" name="target_type">
            <option value="">전체보기</option>
            <?php foreach($point_type as $key=>$val) :?>
                <option value="<?=$key?>" <?=$target_type==$key?'selected':''?>><?=$val?></option>
            <?php endforeach;?>
        </select>
    </div>
</div>

<div class="form-group">
    <label class="control-label control-label-sm">기간 검색</label>
    <div class="controls">
        <input class="form-control form-control-inline" name="startdate" value="<?=$startdate?>" data-toggle="datepicker">
        <input class="form-control form-control-inline" name="enddate" value="<?=$enddate?>" data-toggle="datepicker">
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
<div class="grid">
    <table>
        <thead>
            <tr>
                <th>번호</th>
                <th>일시</th>
                <th>유형</th>
                <th class="col-sm-6">내용</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($point_list['list'] as $row) :?>
            <tr>
                <td><?=$row['nums']?></td>
                <td><?=$row['mpo_regtime']?></td>
                <td><?=point_type($row['target_type'])?></td>
                <td><?=$row['mpo_description']?></td>
            </tr>
        <?php endforeach;?>
        <?php if(count($point_list['list']) == 0) :?>
            <tr>
                <td colspan="4" class="empty"><?=$this->site->config('point_name')?> 내역이 없습니다.</td>
            </tr>
        <?php endif;?>
        </tbody>
    </table>
</div>
<div class="H10"></div>
<div class="ax-button-group ax-button-group-bottom">
    <div class="left">
        <?=$pagination?>        
    </div>
    <div class="right">
        <button type="button" class="btn btn-default" onclick="APP.MEMBER.POP_POINT_FORM_ADMIN('<?=$mem['mem_idx']?>');"><i class="far fa-plus-circle"></i> <?=$this->site->config('point_name')?> 등록</button>
    </div>
</div>

