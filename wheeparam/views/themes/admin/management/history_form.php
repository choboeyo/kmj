<?=validation_errors('<p class="alert alert-danger">')?>
<?=form_open(NULL,array('autocomplete'=>'off'))?>
    <input type="hidden"  name="his_idx" value="<?=element('his_idx',$view)?>">
    <div data-ax-tbl>
        <div data-ax-tr>
            <div data-ax-td>
                <div data-ax-td-label>연도</div>
                <div data-ax-td-wrap>
                    <select class="form-control form-control-inline" name="his_year">
                        <?php for($i=2000; $i<= 2070; $i++){?>
                            <option value="<?=$i?>" <?php if(!empty($view)) {if($view['his_year']==$i) echo 'selected';} else if($i==date('Y')) echo 'selected';?>><?=$i?> 년</option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div data-ax-td>
                <div data-ax-td-label>월</div>
                <div data-ax-td-wrap>
                    <select class="form-control form-control-inline" name="his_month">
                        <?php for($i=1; $i<=12; $i++){?>
                            <option value="<?=$i?>" <?php if(!empty($view)) {if($view['his_month']==$i) echo 'selected';} else if($i==date('m')) echo 'selected';?>><?=$i?> 월</option>
                        <?php }?>
                    </select>
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>내용</div>
                <div data-ax-td-wrap>
                    <input class="form-control" name="his_content" value="<?=element('his_content', $view)?>">
                </div>
            </div>
        </div>

    </div>
    <div class="text-center MT15">
        <button class="btn btn-primary"><i class="fal fa-check"></i> 입력 완료</button>
    </div>
    <div class="H10"></div>
<?=form_close()?>