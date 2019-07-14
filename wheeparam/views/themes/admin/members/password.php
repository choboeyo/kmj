<div class="page-header">
    <h2 class="page-title"><?=$mem['mem_nickname']?>님의 비밀번호 변경</h2>
</div>
<?=validation_errors('<p class="alert alert-danger">')?>
<?=form_open(NULL, array('autocomplete'=>'off','class'=>'form-flex'))?>
<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>새 비밀번호 <span class="text-danger">*</span></div>
            <div data-ax-td-wrap>
                <input type="password" class="form-control" name="mem_password" required>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>비밀번호 확인 <span class="text-danger">*</span></div>
            <div data-ax-td-wrap>
                <input type="password" class="form-control" name="mem_password2" required>
            </div>
        </div>
    </div>
</div>
<div class="text-center MT15">
    <button class="btn btn-primary" onclick="return confirm('해당 사용자의 비밀번호를 변경하시겠습니까?');">비밀번호 변경</button>
</div>
<?=form_close()?>
