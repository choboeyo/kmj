<?=form_open_multipart(NULL ,array('class'=>'form-flex'))?>
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
    </div>
</div>

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

<div class="text-center MT10">
    <button class="btn btn-primary">저장하기</button>
</div>
<?=form_close()?>

<script>
    $(function(){
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