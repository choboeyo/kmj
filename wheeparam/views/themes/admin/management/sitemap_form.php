<?=form_open(NULL, array("class"=>"form-flex"))?>
<div class="form-group">
    <label class="control-label control-label-sm">URL</label>
    <div class="controls">
        <p class="form-control-static form-control-inline"><?=base_url()?></p>
        <input class="form-control form-control-inline" name="sit_loc" required>
    </div>
</div>

<div class="form-group">
    <label class="control-label control-label-sm">중요도</label>
    <div class="controls">
        <input type="number" class="form-control form-control-inline" name="sit_priority" required min="0" max="1" step="0.1" value="0.5">
        <p class="help-block">0~1 까지의 숫자를 입력합니다.</p>
    </div>
</div>

<div class="form-group">
    <label class="control-label control-label-sm">갱신주기</label>
    <div class="controls">
        <select class="form-control form-control-inline" name="sit_changefreq">
            <option value="daily">daily</option>
            <option value="weekly">weekly</option>
            <option value="monthly">monthly</option>
        </select>
    </div>
</div>
<div class="H30"></div>
<div class="text-center">
    <button class="btn btn-primary">저장하기</button>
</div>
<?=form_close()?>
