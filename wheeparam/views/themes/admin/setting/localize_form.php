<?=validation_errors('<p class="alert alert-danger">');?>
<?=form_open(NULL, array('autocomplete'=>'off','class'=>'form-flex'))?>
<div class="form-group">
    <label class="control-label control-label-sm">구분 키</label>
    <div class="controls">
        <input class="form-control" name="loc_key" required>
    </div>
</div>
<div class="form-group">
    <label class="control-label control-label-sm">한글</label>
    <div class="controls">
        <input class="form-control" name="loc_value_ko" required>
    </div>
</div>
<div class="form-group">
    <label class="control-label control-label-sm">English</label>
    <div class="controls">
        <input class="form-control" name="loc_value_en">
    </div>
</div>

<div class="form-group">
    <label class="control-label control-label-sm">일본어</label>
    <div class="controls">
        <input class="form-control" name="loc_value_ja" required>
    </div>
</div>

<div class="form-group">
    <label class="control-label control-label-sm">중국어(간체)</label>
    <div class="controls">
        <input class="form-control" name="loc_value_zh-hans" required>
    </div>
</div>

<div class="form-group">
    <label class="control-label control-label-sm">중국어(번체)</label>
    <div class="controls">
        <input class="form-control" name="loc_value_zh-hant" required>
    </div>
</div>
<div class="text-center MT10">
    <button class="btn btn-primary">추가하기</button>
</div>
<?=form_close()?>
