<div class="cart-pop-layer">
    <?=$form_open?>
    <div class="cart-pop-inner">
        <div class="modify-wrap">
            <h3 class="cart-pop-title">선택 옵션 수량 변경</h3>
            <ul class="cart-selected-options">
                <?php foreach($list as $i=>$row):?>
                    <input type="hidden" name="opt_type[<?=$prd_idx?>][]" value="<?=$row['opt_type']?>">
                    <input type="hidden" name="opt_code[<?=$prd_idx?>][]" value="<?=$row['opt_code']?>">
                    <input type="hidden" name="opt_value[<?=$prd_idx?>][]" value="<?=$row['cart_option']?>">
                    <input type="hidden" name="opt_subject[<?=$prd_idx?>][]" value="<?=$row['opt_subject']?>">
                    <input type="hidden" class="opt_price" value="<?php echo $row['opt_price']; ?>">
                    <input type="hidden" class="opt_stock" value="<?php echo $row['opt_stock_qty']; ?>">

                    <li>
                        <div class="opt-name"><?=$row['cart_option']?></div>
                        <div class="opt-count">
                            <button type="button" class="cart-btn" data-button="cart-modify-minus" data-target="#cart-qty-input-<?=$i?>"><i class="fas fa-minus"></i></button>
                            <input class="cart-input" id="cart-qty-input-<?=$i?>" name="cart_qty[<?=$prd_idx?>][]`" value="<?=$row['cart_qty']?>" data-number-only>
                            <button type="button" class="cart-btn" data-button="cart-modify-plus" data-target="#cart-qty-input-<?=$i?>"><i class="fas fa-plus"></i></button>
                        </div>
                    </li>
                <?php endforeach;?>
            </ul>
        </div>

        <div class="cart-bottom-actions">
            <button type="submit" class="cart-btn large submit">변경적용</button>
            <button type="button" class="cart-btn large" data-button="modify-cart-close">취소하기</button>
        </div>

        <!-- 이미 선택된 목록 -->
    </div>
    <?=$form_close?>
</div>