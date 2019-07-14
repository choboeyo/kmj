<?=form_open(NULL, array('autocomplete'=>'off'))?>
<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>분류 이름</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="qnc_title" value="<?=element('qnc_title', $view)?>" autofocus required>
            </div>
        </div>
    </div>
</div>
<div class="text-center MT15">
    <button class="btn btn-primary"><i class="fal fa-save"></i> 저장하기</button>
</div>
<?=form_close()?>
