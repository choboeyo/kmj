<?php
$pay_method_array['card'] = "신용카드";
$pay_method_array['bank'] = "무통장입금";
$pay_method_array['trans'] = "실시간계좌이체";
$pay_method_array['vbank'] = "가상계좌";
$pay_method_array['phone'] = "휴대폰소액결제";
$pay_method_array['naverpay'] = "네이버페이";
$pay_method_array['samsung'] = "삼성페이";
$pay_method_array['kakaopay'] = "카카오페이";
?>
<div class="container skin-members-basic">
    <h2 class="members-title">주문 상세내역</h2>

    <table class="order-table order-table-view">
        <tr>
            <th>주문일시</th>
            <td><?=$order['od_receipt_time']?></td>
        </tr>
        <tr>
            <th>주문자</th>
            <td><?=$order['od_name']?></td>
        </tr>
        <tr>
            <th>핸드폰</th>
            <td><?=$order['od_hp']?></td>
        </tr>
        <tr>
            <th>전화번호</th>
            <td><?=$order['od_tel']?></td>
        </tr>
        <tr>
            <th>주소</th>
            <td><?=$order['od_addr1']?> <?=$order['od_addr2']?></td>
        </tr>
        <tr>
            <th>E-mail</th>
            <td><?=$order['od_email']?></td>
        </tr>
        <tr>
            <th>배송 메모</th>
            <td><?=$order['od_memo']?></td>
        </tr>
    </table>

    <div class="H30"></div>

    <h2 class="members-title">결제 정보</h2>
    <table class="order-table order-table-view">
        <tr>
            <th>주문총액</th>
            <td><?=number_format($order['od_cart_price'])?> 원</td>
        </tr>
        <tr>
            <th>배송비</th>
            <td><?=number_format($order['od_send_cost'])?> 원</td>
        </tr>
        <tr>
            <th>총계</th>
            <td><?=number_format($order['od_receipt_price'])?> 원</td>
        </tr>
        <tr>
            <th>결제금액</th>
            <td><?=number_format($order['od_receipt_price'] - $order['od_misu'])?> 원</td>
        </tr>
        <tr>
            <th>미결제액</th>
            <td><?=number_format($order['od_misu'])?> 원</td>
        </tr>
        <tr>
            <th>결제방식</th>
            <td><?=$pay_method_array[$order['od_settle_case']]?></td>
        </tr>
        <?php if($order['od_settle_case'] === 'bank'):?>
        <tr>
            <th>입금계좌</th>
            <td><?=$this->site->config('shop_bank_account')?></td>
        </tr>
        <?php endif;?>
    </table>

    <div class="H30"></div>
    
    <h2 class="members-title">배송 정보</h2>
    <table class="order-table order-table-view">
        <tr>
            <th>배송업체</th>
            <td><?=$order['od_delivery_company']?></td>
        </tr>
        <tr>
            <th>송장번호</th>
            <td><?=$order['od_delivery_num']?></td>
        </tr>
    </table>

    <div class="H30"></div>
    <h2 class="members-title">주문 상품 정보</h2>
    <table class="cart-table">
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
                    <div class="prd-options-info">
                        <a class="prd-name" href="<?=$row['link']?>"><?=$row['prd_name']?></a>
                    </div>
                    <?php if(! empty($row['cart_option_array'])):?>
                        <ul class="prd-options-list">
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

    <div class="H30"></div>


</div>