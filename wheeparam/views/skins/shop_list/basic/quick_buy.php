<?=$form_open;?>
<div class="quick-buy">
    <div class="quick-buy-form">
        <?php foreach($options as $key=>$row) :?>
        <div class="quick-option-select">
            <label class="sr-only" for="it_option_<?=$key?>"><?=$row['title']?></label>
            <select class="qbf-select" id="it_option_<?=$key?>" data-product-cart="<?=$key?>" data-label="<?=$row['title']?>">
                <option value=""><?=$row['title']?></option>
                <?php foreach($row['items'] as $opt):?>
                <option value="<?=$opt['value']?>" data-name="<?=$opt['code']?>" data-chained="<?=$opt['parent']?>" data-price="<?=$opt['price']?>" data-stock="<?=$opt['stock']?>"><?=$opt['code']?></option>
                <?php endforeach;?>
            </select>
        </div>
        <?php endforeach;?>
        <button type="submit" class="btn-qbf btn-qbf-submit">장바구니 담기</button>
        <button type="button" class="btn-qbf btn-qbf-close" onclick="APP.SHOP.closeQuickCart()">닫기</button>
    </div>
</div>
<?=$form_close;?>

<script>
    /**
     * 장바구니 추가 완료후 콜백 이벤트 재정의
     */
    APP.SHOP.updateCartCallback = function() {
        alert('장바구니에 상품이 담겼습니다.');
    }
</script>
