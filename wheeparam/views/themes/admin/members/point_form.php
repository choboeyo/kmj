<?=form_open(NULL, array('autocomplete'=>'off','class'=>'form-flex'))?>
<input type="hidden" name="mem_idx" value="<?=$mem_idx?>">
<input type="hidden" name="target_type" value="NONE">
<?=validation_errors('<p class="alert alert-danger">')?>
<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td class="W500">
            <div data-ax-td-label><?=$this->site->config('point_name')?></div>
            <div data-ax-td-wrap>
                <input class="form-control" name="mpo_value" required>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-wrap>
                <select class="form-control" name="mpo_flag">
                    <option value="1">증가</option>
                    <option value="-1">감소</option>
                </select>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label><?=$this->site->config('point_name')?> 내용</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="mpo_description">
            </div>
        </div>
    </div>
</div>
<div class="text-center MT10">
    <button class="btn btn-primary">등록하기</button>
</div>
<?=form_close()?>
