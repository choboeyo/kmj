<?php
$order_sattus['주문'] = '<label class="order-status status-01">입금확인중</label>';
$order_sattus['입금'] = '<label class="order-status status-01">입금완료</label>';
$order_sattus['준비'] = '<label class="order-status status-01">상품준비중</label>';
$order_sattus['배송'] = '<label class="order-status status-01">상품배송</label>';
$order_sattus['완료'] = '<label class="order-status status-01">배송완료</label>';
$order_sattus['취소'] = '<label class="order-status status-01">주문취소</label>';
?>
<div class="container skin-members-basic">

    <?=$asides_member?>

    <h2 class="members-title">내 주문 내역</h2>

    <table class="order-table">
        <thead>
        <tr>
            <th>주문서번호</th>
            <th>주문일시</th>
            <th>상품수</th>
            <th>주문금액</th>
            <th>입금액</th>
            <th>미입금액</th>
            <th>상태</th>
        </tr>
        </thead>
        <tbody>
        <?php if(count($list) === 0) :?>
        <tr>
            <td colspan="7" class="empty">주문 내역이 없습니다.</td>
        </tr>
        <?php endif;?>
        <?php foreach($list as $row) :?>
        <tr>
            <td><a href="<?=base_url('members/my-order/'.$row['od_id'])?>"><?=$row['od_id']?></a></td>
            <td><?=$row['od_receipt_time']?></td>
            <td><?=number_format($row['od_cart_count'])?></td>
            <td><?=number_format($row['od_receipt_price'])?></td>
            <td><?=number_format($row['od_receipt_price']-$row['od_misu'])?></td>
            <td><?=number_format($row['od_misu'])?></td>
            <td><?=$order_sattus[$row['od_status']]?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>

    <div class="H30"></div>

</div>