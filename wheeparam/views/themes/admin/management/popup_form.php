<div class="page-header">
    <h1 class="page-title">팝업 정보 입력</h1>
</div>

<?=validation_errors('<p class="alert alert-danger">')?>
<?=form_open(NULL,array("class"=>'form-flex'))?>
<div class="form-group">
    <label class="control-label">팝업 이름</label>
    <div class="controls">
        <input class="form-control" name="pop_title" value="<?=element('pop_title', $view, set_value('pop_title'))?>" required>
    </div>
</div>
<div class="form-group">
    <label class="control-label">팝업 종류</label>
    <div class="controls">
        <label class="w-radio">
            <input type="radio" name="pop_type" value="Y" <?=element('pop_type', $view, set_value('pop_type', 'Y'))=='Y'?'checked':''?>>
            <span>팝업창</span>
        </label>
        <label class="w-radio">
            <input type="radio" name="pop_type" value="N" <?=element('pop_type', $view, set_value('pop_type', 'Y'))=='N'?'checked':''?>>
            <span>팝업레이어</span>
        </label>
    </div>
</div>
<div class="form-group">
    <label class="control-label">팝업 너비</label>
    <div class="controls">
        <input type="number" min="1" class="form-control form-control-inline" name="pop_width" value="<?=element('pop_width', $view, set_value('pop_width', 600))?>" required> px
    </div>
</div>
<div class="form-group">
    <label class="control-label">팝업 높이</label>
    <div class="controls">
        <input type="number" min="1" class="form-control form-control-inline" name="pop_height" value="<?=element('pop_height', $view, set_value('pop_height', 480))?>" required> px
    </div>
</div>
<div class="form-group">
    <label class="control-label">팝업 내용</label>
    <div class="controls">
        <?=get_editor('pop_content', element('pop_content',$view, set_value('pop_content')) )?>
    </div>
</div>
<div class="form-group">
    <label class="control-label">표시 시작시간</label>
    <div class="controls">
        <input class="form-control form-control-inline" data-toggle="formatter" data-pattern="{{9999}}-{{99}}-{{99}} {{99}}:{{99}}" name="pop_start" value="<?=element('pop_start', $view, set_value('pop_start'))?>" required>
        <p class="help-block">년-월-일 시:분</p>
    </div>
</div>

<div class="form-group">
    <label class="control-label">표시 종료시간</label>
    <div class="controls">
        <input class="form-control form-control-inline" data-toggle="formatter" data-pattern="{{9999}}-{{99}}-{{99}} {{99}}:{{99}}" name="pop_end" value="<?=element('pop_end', $view, set_value('pop_end'))?>" required>
        <p class="help-block">년-월-일 시:분</p>
    </div>
</div>

<div class="H10"></div>
<div class="text-center">
    <button class="btn btn-primary"><i class="far fa-check"></i> 입력 완료</button>
</div>
<div class="H10"></div>
<?=form_close()?>
