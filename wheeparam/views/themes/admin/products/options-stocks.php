<form >
    <div data-ax-tbl>
        <div data-ax-tr>
            <div data-ax-td class="W500">
                <div data-ax-td-label>상품 분류</div>
                <div data-ax-td-wrap>
                    <select class="form-control" name="cat_id">
                        <option value="">전체보기</option>
                        <?php foreach($categoryList as $row) :?>
                            <option value="<?=$row['cat_id']?>" <?=$cat_id===$row['cat_id']?'selected':''?>><?=$row['cat_title']?></option>
                            <?php foreach($row['children'] as $row2) :?>
                                <option value="<?=$row2['cat_id']?>" <?=$cat_id===$row2['cat_id']?'selected':''?>><?=$row2['parent_names']?><?=$row2['cat_title']?></option>
                                <?php foreach($row2['children'] as $row3) :?>
                                    <option value="<?=$row3['cat_id']?>" <?=$cat_id===$row3['cat_id']?'selected':''?>><?=$row3['parent_names']?><?=$row3['cat_title']?></option>
                                <?php endforeach;?>
                            <?php endforeach;?>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td class="W500">
                <div data-ax-td-label>판매상태</div>
                <div data-ax-td-wrap>
                    <label class="w-check">
                        <input type="checkbox" name="prd_sell_status[]" value="Y" <?=in_array('Y', $prd_sell_status)?'checked':''?>>
                        <span class="checkbox-label">판매중</span>
                    </label>
                    <label class="w-check">
                        <input type="checkbox" name="prd_sell_status[]" value="O" <?=in_array('O', $prd_sell_status)?'checked':''?>>
                        <span class="checkbox-label">품절</span>
                    </label>
                    <label class="w-check">
                        <input type="checkbox" name="prd_sell_status[]" value="D" <?=in_array('D', $prd_sell_status)?'checked':''?>>
                        <span class="checkbox-label">일시판매중지</span>
                    </label>
                </div>
            </div>
            <div data-ax-td class="W500">
                <div data-ax-td-label>표시상태</div>
                <div data-ax-td-wrap>
                    <label class="w-check">
                        <input type="checkbox" name="prd_status[]" value="Y" <?=in_array('Y', $prd_status)?'checked':''?>>
                        <span class="checkbox-label">노출중</span>
                    </label>
                    <label class="w-check">
                        <input type="checkbox" name="prd_status[]" value="N" <?=in_array('N', $prd_status)?'checked':''?>>
                        <span class="checkbox-label">감춤</span>
                    </label>
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td>
                <div data-ax-td-label>상품명 검색</div>
                <div data-ax-td-wrap>
                    <input class="form-control" name="stxt" value="<?=$stxt?>">
                </div>
            </div>
        </div>
    </div>
    <div class="text-center MT10">
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> 필터 적용</button>
    </div>
</form>
<div class="H15"></div>

<?=form_open(NULL, ['data-form'=>"form-stocks"])?>
<input type="hidden" name="redirectUrl" value="<?=current_full_url()?>">
<div class="ax-button-group">
    <div class="right">
        <button type="submit" data-button="add-item" class="btn btn-primary"><i class="fas fa-save"></i> 일괄 저장하기</button>
    </div>
</div>

<div class="grid">
    <table style="table-layout: fixed">
        <thead>
        <tr>
            <th class="W80"></th>
            <th class="W120">상품코드</th>
            <th class="W200">상품분류</th>
            <th>상품명</th>
            <th>옵션명</th>
            <th class="W120">옵션구분</th>
            <th class="W100">창고재고</th>
            <th class="W100">주문대기</th>
            <th class="W100">가재고</th>
            <th class="W100">적정재고</th>
            <th class="W100">재고수정</th>
            <th class="W150">추가금액</th>
            <th class="W80">관리</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($list as $row):
            $daegi = $row['opt_stock_qty'] - $this->products_model->getOptionStockQty($row['prd_idx'], $row);
            ?>
        <tr>
            <td class="W80 text-center">
                <input type="hidden" name="opt_idx[]" value="<?=$row['opt_idx']?>">
                <figure class="product-thumb">
                    <?php if($row['prd_thumbnail_path'] && file_exists(FCPATH . $row['prd_thumbnail_path'])):?>
                        <a data-button="zoom-image" href="<?=base_url($row['prd_thumbnail_path'])?>">
                            <img class="W60" src="<?=thumbnail($row['prd_thumbnail_path'], 60)?>">
                        </a>
                    <?php else :?>
                        <img src="http://placehold.it/60x60?text=NO+IMAGE">
                    <?php endif;?>
                </figure>
            </td>
            <td class="text-center"><?=$row['prd_idx']?></td>
            <td class="text-left"><?=$row['parent_names']?><?=$row['cat_title']?></td>
            <td>
                <a href="<?=base_url('products/items/'.$row['prd_idx'].'?preview=1')?>" target="_blank"><?=$row['prd_name']?></a>
            </td>
            <td>
                <ul class="product-options-list">
                    <?php foreach($row['optNamesArray'] as $i=>$name):?>
                    <li>
                        <dl>
                            <?php if($row['opt_type']==='detail' && isset($row['prd_item_options'][$i]) && isset($row['prd_item_options'][$i]['title'])) :?>
                            <dt>[<?=$row['prd_item_options'][$i]['title']?>]</dt>
                            <?php endif;?>
                            <dd><?=$name?></dd>
                        </dl>
                    </li>
                    <?php endforeach;?>
                </ul>

            </td>
            <td class="text-center">
                <?php if($row['opt_type'] == 'detail') :?>필수선택옵션
                <?php  elseif($row['opt_type'] == 'addition') :?>추가옵션
                <?php endif;?>
            </td>
            <td class="text-right"><?=number_format($row['opt_stock_qty'])?></td>
            <td class="text-right"><?=number_format(0)?></td>
            <td class="text-right"><?=number_format($row['opt_stock_qty']-0)?></td>
            <td>
                <input class="form-control text-right" name="opt_stock_qty[]" data-number-format data-number-only value="<?=number_format($row['opt_stock_qty'])?>">
            </td>
            <td>
                <input class="form-control text-right" name="opt_noti_qty[]" data-number-format data-number-only value="<?=number_format($row['opt_noti_qty'])?>">
            </td>
            <td>
                <input class="form-control text-right" name="opt_add_price[]" data-number-format data-number-only value="<?=number_format($row['opt_add_price'])?>">
            </td>
            <td class="text-center">
                <a href="<?=base_url('admin/products/items_form/'.$row['prd_idx'])?>" class="btn btn-default">수정</a>

            </td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<?=form_close()?>

<div class="text-center MT15"><?php echo $pagination?></div>

<script>
    $(function() {
        $('[data-form="form-stocks"]').on('submit', function(e) {
            e.preventDefault();

            var data = $(this).serialize();

            $.ajax({
                url: base_url + '/admin/ajax/products/options-stocks',
                type: 'POST',
                data: data,
                success:function() {
                    location.reload();
                }
            })
        })
    })
</script>
