<div class="page-header">
    <h1 class="page-title">FAQ 환경 설정</h1>
</div>
<?=form_open_multipart("admin/setting/update", array("class"=>"form-flex"))?>
<input type="hidden" name="reurl" value="<?=base_url('admin/management/faq_setting')?>">
<div class="panel panel-dark">
    <div class="panel-heading">
        <h4 class="panel-title">FAQ 설정</h4>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <label class="control-label">FAQ 페이지 제목</label>
            <div class="controls">
                <input name="setting[faq_title]" value="<?=$this->site->config('faq_title')?>" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">FAQ 페이지 설명</label>
            <div class="controls">
                <textarea name="setting[faq_description]" rows="4" class="form-control"><?=$this->site->config('faq_description')?></textarea>
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <button class="btn btn-primary">저장하기</button>
    </div>
</div>
<?=form_close()?>