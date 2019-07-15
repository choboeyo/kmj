<?=form_open(NULL,array('autocomplete'=>'off','class'=>'form-flex'))?>
<input type="hidden" name="brd_key" value="<?=$brd_key?>">
<input type="hidden" name="bca_parent" value="<?=$bca_parent?>">
<input type="hidden" name="bca_idx" value="<?=$bca_idx?>">
<div class="form-group">
    <label class="control-label control-label-sm">카테고리 이름</label>
    <div class="controls">
        <input class="form-control" name="bca_name" value="<?=element('bca_name', $view)?>" required autofocus>
    </div>
</div>
<div class="H10"></div>
<div class="text-center">
    <button class="btn btn-primary"><i class="fal fa-save"></i> 저장하기</button>
</div>
<?=form_close()?>
