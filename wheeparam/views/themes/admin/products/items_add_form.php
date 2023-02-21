<?php echo form_open()?>
<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>상품코드</div>
            <div data-ax-td-wrap>
                <input type="text" class="form-control" value="<?=time()?>" name="prd_idx">
            </div>
        </div>
    </div>
</div>
<div class="text-center MT10">
    <button type="submit" class="btn btn-primary">상품 신규 생성</button>
</div>
<?php echo form_close()?>
