<?=form_open(NULL, array("class"=>"form-flex"))?>
<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>URL</div>
            <div data-ax-td-wrap>
                <div style="display:-ms-flex;display:-webkit-flex;display:flex;">
                    <p class="form-control-static" style="width:auto;"><?=base_url()?></p>
                    <input class="form-control" name="sit_loc" style="width:auto;-webkit-flex:1;-ms-flex:1;flex:1;" required>
                </div>

            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>중요도</div>
            <div data-ax-td-wrap>
                <input type="number" class="form-control form-control-inline" name="sit_priority" required min="0" max="1" step="0.1" value="0.5">
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>갱신주기</div>
            <div data-ax-td-wrap>
                <select class="form-control form-control-inline" name="sit_changefreq">
                    <option value="daily">daily</option>
                    <option value="weekly">weekly</option>
                    <option value="monthly">monthly</option>
                </select>
            </div>
        </div>
    </div>

    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>메모</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="sit_memo">
            </div>
        </div>
    </div>
</div>
<div class="text-center MT15">
    <button class="btn btn-primary">저장하기</button>
</div>
<?=form_close()?>
