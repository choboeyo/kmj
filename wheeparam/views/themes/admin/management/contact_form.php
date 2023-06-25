<input type="hidden" name="con_id" value="<?=element('con_id', $view)?>">
<?=validation_errors('<p class="alert alert-danger">');?>
<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>상담 의뢰인</div>
            <div data-ax-td-wrap>
                <?=element('con_name', $view)?>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>상담 희망 메일</div>
            <div data-ax-td-wrap>
                <?=element('con_mail', $view)?>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>상담 연락처</div>
            <div data-ax-td-wrap>
                <?=element('con_phone', $view)?>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100" style="height: 100px;">
            <div data-ax-td-label>메모</div>
            <div data-ax-td-wrap>
                <label style="width: -webkit-fill-available;">
                    <textarea class="form-control" name="con_memo" readonly style="height: 85px;"><?=element('con_memo', $view)?></textarea>
                </label>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>상담 희망 날짜</div>
            <div data-ax-td-wrap>
                <?=element('reg_datetime', $view)?>
            </div>
        </div>
    </div>
</div>