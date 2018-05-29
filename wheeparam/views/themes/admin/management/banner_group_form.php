<?=form_open(NULL, array('class'=>'form-flex'))?>
<input type="hidden" name="bng_idx" value="<?=element('bng_idx', $view)?>">
<?=validation_errors('<p class="alert alert-danger">');?>
<div class="form-group">
    <label class="control-label">그룹 고유 키</label>
    <div class="controls">
        <input class="form-control" name="bng_key" value="<?=element('bng_key', $view)?>" required maxlength="10" <?=element('bng_key',$view)?'readonly':''?>>
    </div>
</div>
<div class="form-group">
    <label class="control-label">배너 그룹 이름</label>
    <div class="controls">
        <input class="form-control" name="bng_name" value="<?=element('bng_name', $view)?>" required maxlength="50">
    </div>
</div>
<div class="form-group">
    <label class="control-label">권장 너비 (px)</label>
    <div class="controls">
        <input type="number" class="form-control" name="bng_width" value="<?=element('bng_width', $view, 0)?>">
    </div>
</div>
<div class="form-group">
    <label class="control-label">권장 높이 (px)</label>
    <div class="controls">
        <input type="number" class="form-control" name="bng_height" value="<?=element('bng_height', $view, 0)?>">
    </div>
</div>
<?php for($i=1; $i<=5; $i++):?>
<div class="form-group">
    <label class="control-label">추가필드 <?=$i?></label>
    <div class="controls">
        <div class="w-check">
            <label><input type="checkbox" data-toggle="disabled-checked" data-target='[name="bng_ext<?=$i?>"]' name="bng_ext<?=$i?>_use" value="Y" <?=element("bng_ext{$i}_use", $view, 'N')=='Y'?'checked':''?>><span>사용</span></label>
        </div>
        <input class="form-control form-control-inline" name="bng_ext<?=$i?>" value="<?=element('bng_ext'.$i, $view, '')?>">
    </div>
</div>
<?php endfor;?>
<div class="text-center MT10">
    <button class="btn btn-primary">저장하기</button>
</div>
<?=form_close()?>

<script>
    $(function(){
        $('[data-toggle="disabled-checked"]').each(function(){
            $(this).change(function(){
                var $this = $(this);
                var checked =  $this.prop('checked');
                var target = $this.data('target');

                if( checked ) {
                    $(target).removeAttr('disabled');
                }
                else {
                    $(target).attr('disabled','disabled');
                }
            }).change();
        });
    });
</script>