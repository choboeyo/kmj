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
<div class="container skin-shop-basic">

    <h2 class="order-complete-title">상품 주문이 완료되었습니다.</h2>

    <table class="order-table">
        <tr>
            <th>주문번호</th>
            <td><?=$order['od_id']?></td>
        </tr>
        <tr>
            <th>주문 상품</th>
            <td><?=$order['od_title']?></td>
        </tr>
        <tr>
            <th>결제방법</th>
            <td><?=$pay_method_array[$order['od_settle_case']]?></td>
        </tr>
        <tr>
            <th>주문금액</th>
            <td><?=number_format($order['od_cart_price'])?>원</td>
        </tr>
        <tr>
            <th>배송비</th>
            <td><?=number_format($order['od_send_cost'])?>원</td>
        </tr>
        <tr>
            <th>총 금액</th>
            <td><?=number_format($order['od_receipt_price'])?>원</td>
        </tr>        
        <?php if($order['od_settle_case'] === 'bank'):?>
        <tr>
            <th>입금계좌</th>
            <td><?=$this->site->config('shop_bank_account')?></td>
        </tr>
        <?php endif;?>
        <?php if($order['od_misu'] > 0) :?>
        <tr>
            <th>입금할 금액</th>
            <td><?=number_format($order['od_misu'])?>원</td>
        </tr>
        <?php endif;?>        
    </table>
    
    <div class="cart-bottom-actions">
        <a class="cart-btn large submit" href="<?=base_url()?>">메인으로</a>
        <a class="cart-btn large" href="<?=base_url('shop/cart')?>">장바구니</a>
    </div>
</div>