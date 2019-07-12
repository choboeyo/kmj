<div class="page-header">
    <h1 class="page-title">약관 설정</h1>
</div>

<?=form_open_multipart("admin/setting/update")?>
<input type="hidden" name="reurl" value="<?=base_url('admin/setting/agreement')?>">
<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>사이트 이용약관</div>
            <div data-ax-td-wrap>
                <?=get_editor('setting[agreement_site]',$this->site->config('agreement_site'))?>
            </div>
        </div>
    </div>

    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>개인정보 처리방침</div>
            <div data-ax-td-wrap>
                <?=get_editor('setting[agreement_privacy]',$this->site->config('agreement_privacy'))?>
            </div>
        </div>
    </div>
</div>
<div class="text-center MT10">
    <button class="btn btn-primary">저장하기</button>
</div>
<?=form_close()?>
