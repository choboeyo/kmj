<?=form_open_multipart(NULL ,array('class'=>'form-flex','autocomplete'=>'off'))?>
<input type="hidden" name="bng_key" value="<?=$bng_key?>">
<input type="hidden" name="ban_idx" value="<?=element('ban_idx', $view)?>">
<div class="form-group">
    <label class="control-label">배너 이름</label>
    <div class="controls">
        <input class="form-control" name="ban_name" value="<?=element('ban_name', $view)?>" required maxlength="50">
    </div>
</div>

<div class="form-group">
    <label class="control-label">배너 표시/숨김</label>
    <div class="controls">
        <label class="w-radio"><input type="radio" name="ban_status" value="Y" <?=element('ban_status',$view,'Y')=='Y'?'checked':''?>><span>표시</span></label>
        <label class="w-radio"><input type="radio" name="ban_status" value="H" <?=element('ban_status',$view,'Y')=='H'?'checked':''?>><span>숨김</span></label>
    </div>
</div>

<div class="form-group">
    <label class="control-label">배너 파일</label>
    <div class="controls">
        <input type="file" class="form-control" name="userfile">
        <?php if($banner_group['bng_width'] > 0) :?>
        <p class="help-block">권장 너비 : <?=$banner_group['bng_width']?>px</p>
        <?php endif;?>
        <?php if($banner_group['bng_height'] > 0) :?>
            <p class="help-block">권장 높이 : <?=$banner_group['bng_height']?>px</p>
        <?php endif;?>
    </div>
</div>
<hr>
<div class="form-group">
    <label class="control-label">클릭시 이동</label>
    <div class="controls">
        <label class="w-radio"><input type="radio" name="ban_link_use" value="Y" <?=element('ban_link_use',$view,'N')=='Y'?'checked':''?>><span>사용</span></label>
        <label class="w-radio"><input type="radio" name="ban_link_use" value="N" <?=element('ban_link_use',$view,'N')=='N'?'checked':''?>><span>미사용</span></label>
    </div>
</div>


<div class="form-group">
    <label class="control-label">이동 URL</label>
    <div class="controls">
        <input class="form-control" name="ban_link_url" value="<?=element('ban_link_url', $view)?>">
    </div>
</div>

<div class="form-group">
    <label class="control-label">이동 방식</label>
    <div class="controls">
        <label class="w-radio"><input type="radio" name="ban_link_type" value="Y" <?=element('ban_link_type',$view,'N')=='Y'?'checked':''?>><span>새 탭으로</span></label>
        <label class="w-radio"><input type="radio" name="ban_link_type" value="N" <?=element('ban_link_type',$view,'N')=='N'?'checked':''?>><span>현재창에서</span></label>
    </div>
</div>

<hr>

<div class="form-group">
    <label class="control-label">표기 기간</label>
    <div class="controls">
        <select class="form-control form-control-inline" name="ban_timer_use">
            <option value="Y" <?=element('ban_timer_use', $view,'N')=='Y'?'selected':''?>>시간지정</option>
            <option value="N" <?=element('ban_timer_use', $view,'N')=='N'?'selected':''?>>항상표시</option>
        </select>
    </div>
</div>


<div class="form-group">
    <label class="control-label">표기 시작시간</label>
    <div class="controls">
        <input class="form-control form-control-inline" data-toggle="datetimepicker" name="ban_timer_start" value="<?=element('ban_timer_start', $view)?>">
    </div>
</div>


<div class="form-group">
    <label class="control-label">표기 종료시간</label>
    <div class="controls">
        <input class="form-control form-control-inline" data-toggle="datetimepicker" name="ban_timer_end" value="<?=element('ban_timer_end', $view)?>">
    </div>
</div>

<hr>
<?php
for($i=1; $i<=5; $i++):

    if( $banner_group["bng_ext{$i}_use"] === 'Y' ) :
    ?>
    <div class="form-group">
        <label class="control-label"><?=$banner_group["bng_ext{$i}"]?></label>
        <div class="controls">
            <input class="form-control form-control-inline" name="ban_ext<?=$i?>" value="<?=element('ban_ext'.$i, $view)?>">
        </div>
    </div>
    <?php
    endif;
endfor;
?>


<div class="text-center MT10">
    <button class="btn btn-primary">저장하기</button>
</div>
<?=form_close()?>

<script>
    $(function(){

        $('select[name="ban_timer_use"]').change(function(){
            if( $(this).find('option:selected').val() == 'Y' )
            {
                $('[name="ban_timer_start"],[name="ban_timer_end"]').removeAttr('disabled');
            }
            else {
                $('[name="ban_timer_start"],[name="ban_timer_end"]').val('').attr('disabled','disabled');
            }
        }).change();

        $('input[name="ban_link_use"]').change(function(){
            if( $('input[name="ban_link_use"]:checked').val() == 'Y' ) {
                $('input[name="ban_link_url"]').removeAttr('disabled');
                $('input[name="ban_link_type"]').removeAttr('disabled');
            }
            else {
                $('input[name="ban_link_url"]').attr('disabled','disabled');
                $('input[name="ban_link_type"]').attr('disabled','disabled');
            }
        }).change();
    });
</script>