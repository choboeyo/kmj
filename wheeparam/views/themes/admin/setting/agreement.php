<div class="page-header">
    <h1 class="page-title">약관 설정</h1>
</div>

<?=form_open_multipart("admin/setting/update")?>
<input type="hidden" name="reurl" value="<?=base_url('admin/setting/agreement')?>">
<div class="panel panel-dark">
    <div class="panel-heading">
        <h4 class="panel-title">이용약관</h4>
    </div>
    <div class="panel-body no-padding">
        <?=get_editor('setting[agreement_site]',$this->site->config('agreement_site'))?>
    </div>
    <div class="panel-footer">
        <button class="btn btn-primary">저장하기</button>
    </div>
</div>

<div class="panel panel-dark">
    <div class="panel-heading">
        <h4 class="panel-title">개인정보 처리방침</h4>
    </div>
    <div class="panel-body no-padding">
        <?=get_editor('setting[agreement_privacy]',$this->site->config('agreement_privacy'))?>
    </div>
    <div class="panel-footer">
        <button class="btn btn-primary">저장하기</button>
    </div>
</div>
<?=form_close()?>
