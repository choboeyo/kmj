<div class="ax-button-group">
    <div class="left">
        <h4>
            <?=(element('cat_id', $parent, ''))? element('parent_names', $parent, '') . element('cat_title', $parent, '') . ' > ' : '대메뉴 > ' ?>
            <?=(element('cat_id', $view, ''))? element('cat_title', $view, '') . ' 정보 수정':'하위 분류 등록'?>
        </h4>
    </div>
</div>

<form data-form="product-category">
    <input type="hidden" name="cat_id" value="<?=element('cat_id', $view, '')?>">
    <input type="hidden" name="cat_parent_id" value="<?=element('cat_parent_id', $view, '')?>">
    <div data-ax-tbl>
            <div data-ax-tr>
                <div data-ax-td class="width-100">
                    <div data-ax-td-label>상위 분류</div>
                    <div data-ax-td-wrap>
                        <input class="form-control" disabled value="<?=(element('cat_id', $parent, ''))? element('parent_names', $parent, '') . element('cat_title', $parent, ''):'없음'?>">
                    </div>
                </div>
            </div>
        <div data-ax-tr>
            <div data-ax-td>
                <div data-ax-td-label>분류 이름</div>
                <div data-ax-td-wrap>
                    <input class="form-control" name="cat_title" value="<?=element('cat_title', $view, '')?>" required>
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td>
                <div data-ax-td-label>목록 스킨</div>
                <div data-ax-td-wrap>
                    <select class="form-control" name="cat_skin">
                        <option value="">쇼핑몰 기본설정에 따름</option>
                        <?php foreach($skin_list as $skin):?>
                        <option value="<?=$skin?>" <?=$skin===element('cat_skin', $view, '')?'selected':''?>><?=$skin?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <div data-ax-td class="W350">
                <div data-ax-td-label class="W150"l>목록 스킨 (모바일)</div>
                <div data-ax-td-wrap>
                    <select class="form-control" name="cat_skin_m">
                        <option value="">쇼핑몰 기본설정에 따름</option>
                        <?php foreach($skin_list as $skin):?>
                            <option value="<?=$skin?>" <?=$skin===element('cat_skin_m', $view, '')?'selected':''?>><?=$skin?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td>
                <div data-ax-td-label>페이징 사용</div>
                <div data-ax-td-wrap>
                    <select class="form-control" name="cat_use_paging">
                        <option value="T" <?=element('cat_use_paging', $view, 'T')=='T'?'selected':''?>>쇼핑몰 기본설정에 따름</option>
                        <option value="Y" <?=element('cat_use_paging', $view, 'T')=='Y'?'selected':''?>>사용</option>
                        <option value="N" <?=element('cat_use_paging', $view, 'T')=='N'?'selected':''?>>미사용</option>
                    </select>
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>페이지당<br>표시상품수</div>
                <div data-ax-td-wrap>
                    <input type="number" style="max-width:150px" class="form-control text-right" name="cat_page_rows" value="<?=element('cat_page_rows',$view, 0)?>">
                    <p class="help-block">※ 0으로 입력시 쇼핑몰 기본설정에 따름</p>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center MT10">
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 저장하기</button>
    </div>
</form>

