<?php echo form_open()?>
<div data-ax-tbl class="MT10">
    <div class="caption">원본 정보</div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>상품코드</div>
            <div data-ax-td-wrap>
                <input type="text" class="form-control" value="<?=$original['prd_idx']?>" readonly>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>상품이름</div>
            <div data-ax-td-wrap>
                <input type="text" class="form-control" value="<?=$original['prd_name']?>" readonly>
            </div>
        </div>
    </div>
</div>

<div data-ax-tbl class="MT10">
    <div class="caption">복사대상 정보입력</div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>상품코드</div>
            <div data-ax-td-wrap>
                <input type="text" class="form-control" value="<?=time()?>" name="prd_idx">
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>상품명</div>
            <div data-ax-td-wrap>
                <input type="text" class="form-control" value="" name="prd_name" required maxlength="255">
            </div>
        </div>
    </div>
</div>
<div class="text-center MT10">
    <button type="submit" class="btn btn-primary">상품 복사 실행</button>
</div>
<?php echo form_close()?>
