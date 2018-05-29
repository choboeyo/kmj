<?=validation_errors('<p class="alert alert-danger">');?>
<?=form_open(NULL, array('autocomplete'=>'off','class'=>'form-flex'))?>
<div class="form-group">
    <label class="control-label control-label-sm">구분 키</label>
    <div class="controls">
        <input class="form-control" name="loc_key" required>
    </div>
</div>
<?php foreach($accept_langs as $langs):?>
    <div class="form-group">
        <label class="control-label control-label-sm"><?=$lang_name[$langs]?></label>
        <div class="controls">
            <textarea class="form-control" name="loc_value_<?=$langs?>" required></textarea>
        </div>
    </div>
<?php endforeach;?>
<div class="text-center MT10">
    <button class="btn btn-primary">추가하기</button>
</div>
<?=form_close()?>
