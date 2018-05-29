<div class="page-header">
    <h2 class="page-title"><?=$mem['mem_nickname']?>님의 비밀번호 변경</h2>
</div>
<?=validation_errors('<p class="alert alert-danger">')?>
<?=form_open(NULL, array('autocomplete'=>'off','class'=>'form-flex'))?>
<div class="form-group">
    <label class="control-label">새 비밀번호 <span class="text-danger">*</span></label>
    <div class="controls">
        <input type="password" class="form-control form-control-inline" name="mem_password">
    </div>
</div>
<div class="form-group">
    <label class="control-label">새 비밀번호 확인 <span class="text-danger">*</span></label>
    <div class="controls">
        <input type="password" class="form-control form-control-inline" name="mem_password2">
    </div>
</div>
<div class="form-group">
    <label class="control-label"></label>
    <div class="controls">
        <button class="btn btn-primary" onclick="return confirm('해당 사용자의 비밀번호를 변경하시겠습니까?');">비밀번호 변경</button>
    </div>
</div>
<?=form_close()?>
