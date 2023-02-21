<?=validation_errors('<p class="alert alert-danger">')?>
<?=form_open(NULL,array('autocomplete'=>'off'))?>
<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>팝업 이름</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="pop_title" value="<?=element('pop_title', $view, set_value('pop_title'))?>" required>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>팝업 종류</div>
            <div data-ax-td-wrap>
                <label class="w-radio">
                    <input type="radio" name="pop_type" value="Y" <?=element('pop_type', $view, set_value('pop_type', 'Y'))=='Y'?'checked':''?>>
                    <span>새 창</span>
                </label>
                <label class="w-radio">
                    <input type="radio" name="pop_type" value="N" <?=element('pop_type', $view, set_value('pop_type', 'Y'))=='N'?'checked':''?>>
                    <span>레이어</span>
                </label>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>너비 (px)</div>
            <div data-ax-td-wrap>
                <input class="form-control text-right" data-number-only name="pop_width" value="<?=(element('pop_width', $view, set_value('pop_width', 600)))?>" required>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>높이 (px)</div>
            <div data-ax-td-wrap>
                <input class="form-control text-right" data-number-only name="pop_height" value="<?=(element('pop_height', $view, set_value('pop_height', 600)))?>" required>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>팝업 내용</div>
            <div data-ax-td-wrap>
                <?=get_editor('pop_content', element('pop_content',$view, set_value('pop_content')) )?>
            </div>
        </div>
    </div>

    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>표시시작일시</div>
            <div data-ax-td-wrap>
                <div data-toggle="datetime-picker" data-type="datetime" data-name="pop_start" data-value="<?=element('pop_start', $view, set_value('pop_start'))?>"></div>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>표시종료일시</div>
            <div data-ax-td-wrap>
                <div data-toggle="datetime-picker" data-type="datetime" data-name="pop_end" data-value="<?=element('pop_end', $view, set_value('pop_end'))?>"></div>
            </div>
        </div>
    </div>
</div>
<div class="text-center MT15">
    <button class="btn btn-primary"><i class="fal fa-check"></i> 입력 완료</button>
</div>
<div class="H10"></div>
<?=form_close()?>

<script>
    $(function() {
        $('[data-toggle="datetime-picker"]').each(function() {
            var name = $(this).data('name'),
                value = $(this).data('value') && $(this).data('value') != '0000-00-00 00:00:00' ? new Date($(this).data('value')) : (new Date()).dateFormat('yyyy-MM-dd 00:00'),
                type = $(this).data('type');

            $(this).dxDateBox({
                type: type,
                value: value,
                displayFormat: type == 'datetime' ? "yyyy-MM-dd HH:mm" : 'yyyy-MM-dd',
                applyButtonText:'적용',
                cancelButtonText:'취소'
            });
            $(this).find('input').attr('name', name);
        });
    });
</script>