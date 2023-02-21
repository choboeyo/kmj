<?php
// 이미지 확대 기능에 필요한 jquey plugin 로드
$this->site->add_js('https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js');
$this->site->add_css('https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css');
?>

<form>
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
<div class="ax-button-group">
    <div class="left">
        <h4 class="page-title">상품 관리 <small>(총 검색수 : <?=number_format($totalCount)?>)</small></h4>
    </div>
    <div class="right">
        <button type="button" data-button="add-item" class="btn btn-primary"><i class="fas fa-plus"></i> 신규 상품 등록</button>
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
            <th class="W100">정가</th>
            <th class="W100">판매가</th>
            <th class="W80">현재 재고</th>
            <th class="W80">표시상태</th>
            <th class="W100">판매상태</th>
            <th class="W80">조회수</th>
            <th class="W80">판매수</th>
            <th class="W80">찜</th>
            <th class="W120">관리</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($list as $row):?>
        <tr>
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
            <td class="text-center"><?=$row['prd_idx']?></td>
            <td class="text-left"><?=$row['parent_names']?><?=$row['cat_title']?></td>
            <td>
                <a href="<?=base_url('products/items/'.$row['prd_idx'].'?preview=1')?>" target="_blank"><?=$row['prd_name']?></a>
                <?php if($row['prd_is_best'] === 'Y'):?><label class="label-success label ML5">BEST</label><?php endif;?>
                <?php if($row['prd_is_hit'] === 'Y'):?><label class="label-danger label ML5">HIT</label><?php endif;?>
                <?php if($row['prd_is_new'] === 'Y'):?><label class="label-warning label ML5">NEW</label><?php endif;?>
                <?php if($row['prd_is_recommend'] === 'Y'):?><label class="label ML5">MD추천</label><?php endif;?>
                <?php if($row['prd_is_sale'] === 'Y'):?><label class="label-danger label ML5">할인</label><?php endif;?>
            </td>
            <td class="text-right"><i class="far fa-won"></i><?=number_format($row['prd_cust_price'])?></td>
            <td class="text-right"><i class="far fa-won"></i><?=number_format($row['prd_price'])?></td>
            <td class="text-right"><?=number_format($row['prd_stock_qty'])?></td>
            <td class="text-center">
                <?php if($row['prd_status'] === 'Y') :?><label class="label label-success">표시중</label>
                <?php elseif($row['prd_status'] === 'H'):?><label class="label label-danger">감춤</label>
                <?php endif;?>
            </td>
            <td class="text-center">
                <?php if($row['prd_sell_status'] === 'Y'):?><label class="label label-success">판매중</label>
                <?php elseif($row['prd_sell_status'] === 'O'):?><label class="label label-danger">품절</label>
                <?php elseif($row['prd_sell_status'] === 'D'):?><label class="label label-warning">일시판매중지</label>
                <?php endif;?>
            </td>
            <td class="text-right"><?=number_format($row['prd_hit'])?></td>
            <td class="text-right"><?=number_format($row['prd_sell_count'])?></td>
            <td class="text-right"><?=number_format($row['prd_wish_count'])?></td>
            <td class="text-center">
                <div class="btn-group">
                    <a href="<?=base_url('admin/products/items_form/'.$row['prd_idx'])?>" class="btn btn-default">수정</a>
                    <button type="button" class="btn btn-default" data-button="copy-item" data-idx="<?=$row['prd_idx']?>">복사</button>
                </div>
            </td>
        </tr>
        <?php endforeach;?>
        <?php if(count($list) == 0) :?>
        <tr>
            <td colspan="13" class="empty">등록된 상품이 없습니다.</td>
        </tr>
        <?php endif;?>
        </tbody>
    </table>
    <div class="text-center MT15"><?php echo $pagination?></div>
</div>

<script>

    $('[data-button="add-item"]').click(function(){
        APP.MODAL.open({
            iframe : {
                url : base_url + '/admin/products/items_add_form',
                param : {}
            },
            width: 500,
            height: 400,
            header : {
                title : '신규 상품 등록'
            }
        });
    });

    $('[data-button="copy-item"]').click(function(){
        var prd_idx = $(this).data('idx');

        APP.MODAL.open({
            iframe : {
                url : base_url + '/admin/products/items_copy_form/' + prd_idx,
                param : {}
            },
            width: 500,
            height: 400,
            header : {
                title : '상품 복사'
            }
        });
    });

    $('[data-button="zoom-image"]').magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        image: {
            verticalFit: false
        }
    });
</script>