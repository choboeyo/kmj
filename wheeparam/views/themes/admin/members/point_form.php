<?=form_open(NULL, array('autocomplete'=>'off','class'=>'form-flex'))?>
<input type="hidden" name="mem_idx" value="<?=$mem_idx?>">
<input type="hidden" name="target_type" value="NONE">
<?=validation_errors('<p class="alert alert-danger">')?>
<div class="form-group">
    <label class="control-label control-label-sm"><?=$this->site->config('point_name')?></label>
    <div class="controls">
        <input class="form-control form-control-inline text-right" name="mpo_value">
        <p class="help-block"><?=$this->site->config('point_name')?> 차감시 - 를 사용해주세요</p>
    </div>
</div>
<div class="form-group">
    <label class="control-label control-label-sm"><?=$this->site->config('point_name')?> 내용</label>
    <div class="controls">
        <input class="form-control" name="mpo_description">
    </div>
</div>
<div class="text-center MT10">
    <button class="btn btn-primary">등록하기</button>
</div>
<?=form_close()?>
