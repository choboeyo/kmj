<form>
    <div data-ax-tbl>
        <div data-ax-tr>
            <div data-ax-td class="width-100">
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
            <div data-ax-td class="width-100">
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
        </div>
        <div data-ax-tr>
            <div data-ax-td class="width-100">
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
            <div data-ax-td class="width-100">
                <div data-ax-td-label>상품명 검색</div>
                <div data-ax-td-wrap>
                    <input class="form-control" name="stxt" value="<?=$stxt?>">
                </div>
            </div>
        </div>
    </div>
    <div class="text-center MT5">
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> 필터 적용</button>
    </div>
</form>
<div class="H10"></div>
<div class="grid">
    <table style="table-layout:fixed;">
        <thead>
        <tr>
            <th class="W40 text-center">
                <label class="w-check">
                    <input type="checkbox" data-checkbox="list" data-checkbox-all>
                    <span></span>
                </label>
            </th>
            <th class="W80"></th>
            <th class="W200">상품분류</th>
            <th>상품명</th>
            <th class="W100">판매상태</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($list as $row):?>
            <tr>
                <td class="text-center W40">
                    <label class="w-check">
                        <input type="checkbox" name="prd_idx[]" value="<?=$row['prd_idx']?>"  data-checkbox="list">
                        <span></span>
                    </label>
                </td>
                <td class="W80 text-center">
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
                <td class="text-left"><?=$row['parent_names']?><?=$row['cat_title']?></td>
                <td><?=$row['prd_name']?></td>
                <td class="text-center">
                    <?php if($row['prd_sell_status'] === 'Y'):?><label class="label label-success">판매중</label>
                    <?php elseif($row['prd_sell_status'] === 'O'):?><label class="label label-danger">품절</label>
                    <?php elseif($row['prd_sell_status'] === 'D'):?><label class="label label-warning">일시판매중지</label>
                    <?php endif;?>
                </td>
            </tr>
        <?php endforeach;?>
        <?php if(count($list) == 0) :?>
            <tr>
                <td colspan="6" class="empty">등록된 상품이 없습니다.</td>
            </tr>
        <?php endif;?>
        </tbody>
    </table>
    <div class="ax-button-group MT15">
        <div class="left">
            <button type="button" data-button="add_items" class="btn btn-default">선택 추가하기</button>
        </div>
        <div class="right">
            <?php echo $pagination?>
        </div>
    </div>
</div>

<style>
    .pagination {
        text-align: right;
    }
</style>

<script>
    $(function() {
        $('[data-button="add_items"]').click(function() {
            var prd_idx = [];
            $('[name="prd_idx[]"]').each(function() {
                if($(this).val().trim() === "") return;
                if($(this).prop('checked')) {
                    prd_idx.push($(this).val())
                }
            });

            if(prd_idx.length === 0) {
                alert('진열장에 추가할 상품을 먼저 선택해주세요');
                return;
            }

            if(! confirm('선택하신 ' + prd_idx.length + '개의 품목을 진열장에 추가하시겠습니까?')) return;

            $.ajax({
                url: base_url + '/admin/ajax/products/display_items',
                type: 'POST',
                data: {
                    dsp_idx: '<?=$dsp_idx?>',
                    prd_idx: prd_idx
                },
                success: function() {
                    if(confirm('진열장에 품목이 추가되었습니다.\n현재 창을 닫으시겠습니까?')) {
                        parent.window.location.reload();
                    } else {
                        location.reload();
                    }
                }
            })
        })
    })
</script>