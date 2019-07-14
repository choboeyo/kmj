<?=form_open()?>
<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>제목</div>
            <div data-ax-td-wrap>
                <p class="form-control-static"><?=$view['qna_title']?></p>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>작성자</div>
            <div data-ax-td-wrap>
                <p class="form-control-static"><?=$view['qna_name']?></p>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>연락처</div>
            <div data-ax-td-wrap>
                <p class="form-control-static"><?=$view['qna_phone']?></p>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>E-mail</div>
            <div data-ax-td-wrap>
                <p class="form-control-static"><?=$view['qna_email']?></p>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>내용</div>
            <div data-ax-td-wrap>
                <p class="form-control-static"><?=nl2br($view['qna_content'])?></p>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>답변작성여부</div>
            <div data-ax-td-wrap>
                <p class="form-control-static"><?=$view['qna_ans_status']=='Y'?'답변완료':'미답변'?></p>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>답변작성자</div>
            <div data-ax-td-wrap>
                <p class="form-control-static"><?=$view['qna_ans_status']=='Y'?$view['qna_ans_upd_username']:''?></p>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>답변작성시간</div>
            <div data-ax-td-wrap>
                <p class="form-control-static"><?=$view['qna_ans_status']=='Y'?$view['qna_ans_upd_datetime']:''?></p>
            </div>
        </div>
    </div>

    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>답변 작성</div>
            <div data-ax-td-wrap>
                <textarea class="form-control" name="qna_ans_content" data-autosize rows="4"><?=$view['qna_ans_content']?></textarea>
            </div>
        </div>
    </div>
</div>
<div class="text-center MT15">
    <button class="btn btn-primary">답변 작성하기</button>
</div>
<?=form_close()?>
