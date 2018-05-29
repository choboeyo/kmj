<?=form_open(NULL, array('class'=>'form-flex'))?>
<input type="hidden" name="bng_idx" value="<?=element('bng_idx', $view)?>">
<?=validation_errors('<p class="alert alert-danger">');?>
<div class="form-group">
    <label class="control-label">그룹 고유 키</label>
    <div class="controls">
        <input class="form-control" name="bng_key" value="<?=element('bng_key', $view)?>" required maxlength="10" <?=element('bng_key',$view)?'readonly':''?>>
    </div>
</div>
<div class="form-group">
    <label class="control-label">배너 그룹 이름</label>
    <div class="controls">
        <input class="form-control" name="bng_name" value="<?=element('bng_name', $view)?>" required maxlength="50">
    </div>
</div>
<div class="text-center MT10">
    <button class="btn btn-primary">저장하기</button>
</div>
<?=form_close()?>
