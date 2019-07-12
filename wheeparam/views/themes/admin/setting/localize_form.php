<?=validation_errors('<p class="alert alert-danger">');?>
<?=form_open(NULL, array('autocomplete'=>'off','class'=>'form-flex'))?>
<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>구분 키</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="loc_key" required maxlength="60">
            </div>
        </div>
    </div>
    <?php foreach($accept_langs as $langs):?>
        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label><?=$lang_name[$langs]?></div>
                <div data-ax-td-wrap>
                    <textarea class="form-control" name="loc_value_<?=$langs?>" data-autosize required></textarea>
                </div>
            </div>
        </div>
    <?php endforeach;?>
</div>
<div class="text-center MT10">
    <button class="btn btn-primary">추가하기</button>
</div>
<?=form_close()?>
