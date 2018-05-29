<div class="page-header">
    <h1 class="page-title">회원 설정</h1>
</div>

<?=form_open("admin/setting/update",array('class'=>'form-flex'))?>
<input type="hidden" name="reurl" value="<?=base_url('admin/setting/member')?>">
<div class="panel panel-dark">
    <div class="panel-heading">
        <h4 class="panel-title"><?=$this->site->config('point_name')?> 설정</h4>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <label class="control-label"><?=$this->site->config('point_name')?></label>
            <div class="controls">
                <label class="w-radio"><input type="radio" class="radio-point-use" name="setting[point_use]" value="Y" <?=$this->site->config('point_use')=='Y'?'checked':''?>><span>사용</span></label>
                <label class="w-radio"><input type="radio" class="radio-point-use" name="setting[point_use]" value="N" <?=$this->site->config('point_use')=='N'?'checked':''?>><span>미사용</span></label>
            </div>
        </div>
        <div id="point-fieldset">
            <div class="form-group">
                <label class="control-label"><?=$this->site->config('point_name')?> 명칭</label>
                <div class="controls">
                    <input class="form-control form-control-inline" name="setting[point_name]" value="<?=$this->site->config('point_name')?>">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label">회원가입 지급 <?=$this->site->config('point_name')?></label>
                <div class="controls">
                    <input class="form-control form-control-inline" name="setting[point_member_register]" value="<?=$this->site->config('point_member_register')?>">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label">로그인 지급 <?=$this->site->config('point_name')?></label>
                <div class="controls">
                    <input class="form-control form-control-inline" name="setting[point_member_login]" value="<?=$this->site->config('point_member_login')?>">
                </div>
            </div>

        </div>
    </div>
    <div class="panel-footer">
        <button type="submit" class="btn btn-primary">저장하기</button>
    </div>
</div>
<?=form_close()?>

<script>
$(document).ready(function(){
    $('input[type=radio].radio-point-use').change(function(){
        if( $('input[type=radio].radio-point-use:checked').val() == 'Y' )
        {
            $('#point-fieldset').removeAttr('readonly');
            $('#point-fieldset .form-control').removeAttr('readonly');
        }
        else {
            $('#point-fieldset').attr('readonly','readonly');
            $('#point-fieldset .form-control').attr('readonly','readonly');
        }
    }).change();
});
</script>
