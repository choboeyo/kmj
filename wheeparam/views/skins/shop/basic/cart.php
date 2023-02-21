<div class="skin-shop-basic container" data-page="shop-cart">
    <?=$form_open?>
        <table class="cart-table">
            <thead>
            <tr>
                <th class="th-check">
                    <label class="cart-check">
                        <input type="checkbox" data-checkbox="cart" data-checkbox-all checked>
                        <span></span>
                    </label>
                </th>
                <th class="th-name" scope="col" colspan="2">상품명</th>
                <th class="th-qty" scope="col">총수량</th>
                <th class="th-price" scope="col">판매가</th>
                <th class="th-send-cost" scope="col">배송비</th>
                <th class="th-total" scope="col">소계</th>
            </tr>
            </thead>
            <tbody>
            <?php if(count($list) === 0) :?>
                <tr>
                    <td colspan="7" class="empty">장바구니에 담긴 상품이 없습니다.</td>
                </tr>
            <?php endif;?>
            <?php foreach($list as $row) :?>
                <tr>
                    <td class="td-check">
                        <label class="cart-check">
                            <input type="checkbox" name="prd_idx[]" value="<?=$row['prd_idx']?>" data-checkbox="cart" checked>
                            <span></span>
                        </label>
                    </td>
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

                        <div class="button-actions">
                            <button type="button" class="cart-btn" data-button="modify-cart-option" data-idx="<?=$row['prd_idx']?>" data-direct="<?=$is_direct?'Y':'N'?>">변경</button>
                        </div>
                    </td>
                    <td class="text-right td-cart-price"><?=number_format($row['cart_price'])?>원</td>
                    <td class="text-center"><?=$row['send_cost']?></td>
                    <td class="text-right td-sell-price"><strong><?=number_format($row['sell_price'])?></strong>원</td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>

        <?php if(count($list) >0):?>
        <div class="cart-table-actions">
            <button type="button" class="cart-btn" data-button="delete-selected-cart" data-direct="<?=$is_direct?'Y':'N'?>">선택삭제</button>
            <button type="button" class="cart-btn" data-button="delete-all-cart" data-direct="<?=$is_direct?'Y':'N'?>">비우기</button>
        </div>
        <?php endif;?>

        <?php if($send_cost>0 OR $total_price > 0 OR $total_sell_price > 0) :?>
        <div class="cart-summary" v-if="send_cost>0||total_price>0||total_sell_price>0">
            <dl class="summary-total-sell-price">
                <dt>주문금액</dt>
                <dd><strong><?=number_format($total_sell_price)?></strong> 원</dd>
            </dl>
            <dl class="summary-send-cost">
                <dt>배송비</dt>
                <dd><strong><?=number_format($send_cost)?></strong> 원</dd>
            </dl>
            <dl class="summary-total">
                <dt>결제 금액</dt>
                <dd><strong><?=number_format($total_price)?></strong> 원</dd>
            </dl>
        </div>
        <?php endif;?>

        <div class="cart-bottom-actions">
            <?php if(count($list) == 0) :?>
                <a href="<?=base_url()?>" class="cart-btn large">쇼핑 계속하기</a>
            <?php else :?>
                <a href="<?=base_url('products/category/'.$prev_cat_id)?>" class="cart-btn large">쇼핑 계속하기</a>
                <?php if($this->member->is_login()):?>
                <button type="submit" class="cart-btn large submit">주문하기</button>
                <?php else:?>
                <a href="<?=base_url('members/login?reurl='.rawurlencode(base_url('shop/cart')))?>">주문하기</a>
                <?php endif;?>
            <?php endif;?>
        </div>

    <?=$form_close?>

    <!-- S: 선택옵션 수정 폼 -->
    <div data-container="cart-modify-form"></div>
    <!-- E: 선택옵션 수정 폼 -->
</div>
