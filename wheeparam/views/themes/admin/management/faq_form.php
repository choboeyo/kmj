<?=form_open(NULL, array("class"=>"form-flex")) ?>
<input type="hidden" name="faq_idx" value="<?=element('faq_idx', $view)?>">
<input type="hidden" name="fac_idx" value="<?=element('fac_idx', $faq_group)?>">
<?=validation_errors('<p class="alert alert-danger">') ?>
    <div class="form-group">
        <label class="control-label">그룹 이름</label>
        <div class="controls">
            <p class="form-control-static"><?=$faq_group['fac_title']?></p>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">FAQ 이름</label>
        <div class="controls">
            <input type="text" class="form-control" name="faq_title" value="<?=element('faq_title', $view) ?>" required maxlength="50" autofocus>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">FAQ 내용</label>
        <div class="controls">
            <?=get_editor('faq_content', element('faq_content', $view), 'form-control', true, 'ckeditor')?>
        </div>
    </div>
    <div class="H10"></div>
    <div class="text-center">
        <button class="btn btn-primary"><i class="far fa-check-circle"></i> 확인</button>
        <button type="button" class="btn btn-default" onclick="parent.APP.MODAL.close();">닫기</button>
    </div>
<?=form_close()?>