<?=form_open()?>
<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>고유 KEY</div>
            <div data-ax-td-wrap>
                <input type="text" class="form-control W200" name="dsp_key" value="<?=element('dsp_key', $view, '')?>" required <?=element('dsp_key', $view, '')?'readonly':''?> maxlength="30">
                <p class="help-block">영문, 숫자, 언더스코어(_), 하이픈(-) 가능</p>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>진열장 이름</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="dsp_title" value="<?=element('dsp_title', $view, '')?>" required maxlength="50">
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>목록 스킨</div>
            <div data-ax-td-wrap>
                <select class="form-control" name="dsp_skin">
                    <?php foreach($skin_list as $skin):?>
                    <option value="<?=$skin?>" <?=$skin===element('dsp_skin', $view, '')?'selected':''?>><?=$skin?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>목록 스킨 (M)</div>
            <div data-ax-td-wrap>
                <select class="form-control" name="dsp_skin_m">
                    <?php foreach($skin_list as $skin):?>
                        <option value="<?=$skin?>" <?=$skin===element('dsp_skin_m', $view, '')?'selected':''?>><?=$skin?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
    </div>
</div>

<?=validation_errors('<p class="alert alert-danger MT10">')?>

<div class="text-center MT10">
    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> 저장하기</button>
</div>
<?=form_close()?>

<script>
$(function() {
    $('[name="dsp_key"]').blur(function() {
        var value = $(this).val().trim();

        if(value === '') return;

        var regex =  /^[_A-Za-z0-9+]*$/ ;
        if(! regex.test(value)) {
            alert('고유 KEY 는 영문, 숫자, 언더스코어(_), 하이픈(-)만 사용가능합니다.');
            $(this).focus();
            return;
        }
    })
})
</script>