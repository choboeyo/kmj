<?php echo form_open()?>
<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>상품 분류</div>
            <div data-ax-td-wrap>
                <input value="<?=$view['parent_names']?><?=$view['cat_title']?>" class="form-control" readonly>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>문의 상품</div>
            <div data-ax-td-wrap>
                <input value="<?=$view['prd_name']?>" class="form-control" readonly>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>문의 일시</div>
            <div data-ax-td-wrap>
                <input value="<?=$view['reg_datetime']?>" class="form-control W200" readonly>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>문의자</div>
            <div data-ax-td-wrap>
                <input value="<?=$view['nickname']?>" class="form-control W200" readonly>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>비밀글</div>
            <div data-ax-td-wrap>
                <label class="toggle-box">
                    <input type="radio" name="qa_secret" value="Y" <?=$view['qa_secret']==='Y'?'checked':''?>>
                    <span>비밀글</span>
                </label>
                <label class="toggle-box">
                    <input type="radio" name="qa_secret" value="N" <?=$view['qa_secret']==='N'?'checked':''?>>
                    <span>공개글</span>
                </label>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>문의내용</div>
            <div data-ax-td-wrap>
                <textarea class="form-control" readonly data-autosize="textarea"><?=$view['qa_content']?></textarea>
            </div>
        </div>
    </div>
    <?php if($view['qa_is_answer'] == 'Y'):?>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>답변자</div>
            <div data-ax-td-wrap>
                <input value="<?=$view['a_nickname']?>" class="form-control W200" readonly>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>답변일시</div>
            <div data-ax-td-wrap>
                <input value="<?=$view['qa_a_datetime']?>" class="form-control W200" readonly>
            </div>
        </div>
    </div>
    <?php endif;?>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>답변작성</div>
            <div data-ax-td-wrap>
                <textarea class="form-control" name="qa_a_content" data-autosize="textarea"><?=$view['qa_a_content']?></textarea>
            </div>
        </div>
    </div>
</div>
<?=validation_errors('<p class="alert alert-danger MT10">')?>
<div class="text-center MT10">
    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> 답변저장</button>
</div>
<?php echo form_close()?>
