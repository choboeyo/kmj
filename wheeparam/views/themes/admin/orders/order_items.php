<div class="grid">
<table style="table-layout: fixed">
    <colgroup>
        <col class="W100" />
        <col />
        <col class="W100">
        <col class="W100">
        <col class="W100">
        <col class="W100">
    </colgroup>
    <thead>
    <tr>
        <th class="th-name" scope="col" colspan="2">상품명</th>
        <th class="th-qty" scope="col">총수량</th>
        <th class="th-price" scope="col">판매가</th>
        <th class="th-send-cost" scope="col">배송비</th>
        <th class="th-total" scope="col">소계</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($list as $row) :?>
        <tr>
            <td class="td-thumb">
                <?php if($row['thumbnail']):?>
                    <img src="<?=thumbnail($row['thumbnail'], 80)?>" />
                <?php else :?>
                    <img src="http://placehold.it/80x80?text=NO+IMAGE" alt="상품 이미지 없음">
                <?php endif;?>
            </td>
            <td class="td-name">
                <div class="prd-options-info" style="margin-bottom:10px;">
                    <a class="prd-name" href="<?=$row['link']?>"><?=$row['prd_name']?></a>
                </div>
                <?php if(! empty($row['cart_option_array'])):?>
                    <ul class="prd-options-list" style="list-style:none;padding:0; margin:0;">
                        <?php foreach($row['cart_option_array'] as $opt):?>
                            <li>
                                <span class="opt-label"><?=$opt['opt_type']==='detail'?'필수옵션':'추가옵션'?></span>
                                <span class="opt-name"><?=$opt['cart_option']?></span>
                                <span class="opt-price">(<?=$opt['opt_price']>0?'+':''?><?=number_format($opt['opt_price'])?>원)</span>
                                <span class="opt-count"><?=$opt['cart_qty']?> 개</span>
                            </li>
                        <?php endforeach;?>
                    </ul>
                <?php endif;?>
            </td>
            <td class="text-center">
                <?=number_format($row['sell_qty'])?>
            </td>
            <td class="text-right td-cart-price"><?=number_format($row['cart_price'])?>원</td>
            <td class="text-center"><?=$row['send_cost']?></td>
            <td class="text-right td-sell-price"><strong><?=number_format($row['sell_price'])?></strong>원</td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
</div>