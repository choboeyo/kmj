<?=validation_errors('<p class="alert alert-danger">')?>
<?=form_open(NULL, array('class'=>'form-flex'))?>
    <div class="form-group">
        <label class="control-label control-label-sm">원본 게시판</label>
        <div class="controls">
            <input class="form-control" value="<?=$view['brd_title']?>" disabled>
            <input type="hidden" name="original" value="<?=$view['brd_key']?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label control-label-sm">고유키</label>
        <div class="controls">
            <input class="form-control" name="brd_key" value="" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label control-label-sm">게시판 이름</label>
        <div class="controls">
            <input class="form-control" name="brd_title" value="" required>
        </div>
    </div>
    <div class="text-center MT10">
        <button class="btn btn-primary">저장하기</button>
    </div>
<?=form_close()?>