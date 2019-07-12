<?=form_open(NULL, array("class"=>"form-flex")) ?>
<input type="hidden" name="faq_idx" value="<?=element('faq_idx', $view)?>">
<input type="hidden" name="fac_idx" value="<?=element('fac_idx', $faq_group)?>">
<?=validation_errors('<p class="alert alert-danger">') ?>
<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>그룹 이름</div>
            <div data-ax-td-wrap>
                <p class="form-control-static"><?=$faq_group['fac_title']?></p>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>FAQ 이름</div>
            <div data-ax-td-wrap>
                <input type="text" class="form-control" name="faq_title" value="<?=element('faq_title', $view) ?>" required maxlength="50" autofocus>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>FAQ 내용</div>
            <div data-ax-td-wrap>
                <?=get_editor('faq_content', element('faq_content', $view), 'form-control', true, 'ckeditor')?>
            </div>
        </div>
    </div>
</div>
<div class="text-center MT10">
    <button class="btn btn-primary"><i class="far fa-check-circle"></i> 확인</button>
    <button type="button" class="btn btn-default" onclick="parent.APP.MODAL.close();">닫기</button>
</div>
<?=form_close()?>