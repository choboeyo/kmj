<div class="container skin-shop-basic">
    <?=$form_open?>
    <!--S: 주문할 상품 목록 보여주기 -->
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
        <?php if(count($cart_list) === 0) :?>
            <tr>
                <td colspan="6" class="empty">장바구니에 담긴 상품이 없습니다.</td>
            </tr>
        <?php endif;?>
        <?php foreach($cart_list as $row) :?>
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
    <!-- E: 주문할 상품 목록 보여주기 -->

    <!-- S: 좌/우 나누기 -->
    <div class="order-wrap">

        <!-- S: 사용자 정보 입력-->
        <div class="left">

            <fieldset>
                <legend class="field-caption">주문자 정보 입력</legend>
                <div class="ord-form-group">
                    <input class="ord-form-input" id="ord-name" name="od_name" required maxlength="20" placeholder="주문자 성함" value="<?=$order_form['nickname']?>">
                    <label class="ord-form-label" for="ord-name">이름 <span class="required">(필수입력)</span></label>
                </div>
                <div class="ord-form-group">
                    <input class="ord-form-input" id="ord-hp" name="od_hp" required maxlength="20" placeholder="주문자 핸드폰 번호" data-regex="phone-number" value="<?=$order_form['phone']?>">
                    <label class="ord-form-label" for="ord-hp">핸드폰 <span class="required">(필수입력)</span></label>
                </div>
                <div class="ord-form-group">
                    <input class="ord-form-input" id="ord-tel" name="od_tel" maxlength="20" placeholder="주문자 전화번호" data-regex="tel-number" value="<?=$order_form['tel']?>">
                    <label class="ord-form-label" for="ord-tel">전화번호</label>
                </div>

                <div class="ord-d-flex">
                    <div class="ord-form-group" style="width:120px;">
                        <input class="ord-form-input" data-input="zonecode" id="ord-zonecode" name="od_zonecode" maxlength="5" placeholder="우편번호" readonly value="<?=$order_form['zonecode']?>">
                        <label class="ord-form-label" for="ord-zonecode">우편번호 <span class="required">(필수입력)</span></label>
                    </div>
                    <button type="button" class="cart-btn" data-button="search-zonecode">우편번호 검색</button>
                </div>
                <div class="ord-form-group">
                    <input class="ord-form-input" data-input="address" id="ord-address1" name="od_addr1" maxlength="100" placeholder="주소" readonly value="<?=$order_form['addr1']?>">
                    <label class="ord-form-label" for="ord-address1">주소 <span class="required">(필수입력)</span></label>
                </div>
                <div class="ord-form-group">
                    <input class="ord-form-input" data-input="addressDetail" id="ord-address2" name="od_addr2" maxlength="100" placeholder="상세 주소" value="<?=$order_form['addr2']?>">
                    <label class="ord-form-label" for="ord-address2">상세 주소</label>
                </div>
                <div class="ord-form-group">
                    <input class="ord-form-input" id="ord-email" name="od_email" maxlength="100" placeholder="주문자 E-mail" value="<?=$this->member->info('email')?>">
                    <label class="ord-form-label" for="ord-email">E-mail</label>
                </div>
                <div class="ord-form-group">
                    <textarea class="ord-form-input" id="ord-memo" name="od_memo" maxlength="100" placeholder="전하실 말씀" rows="4"></textarea>
                    <label class="ord-form-label" for="ord-memo">전하실 말씀</label>
                </div>
            </fieldset>
        </div>
        <!-- E: 사용자 정보 입력-->

        <!-- S: 결제정보 -->
        <div class="left">

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

            <!-- S: 결제수단-->
            <div class="pay-method-wrap">
                <?php foreach($pay_methods as $pay_method):?>
                    <label class="pay-method pay-method-<?=$pay_method['value']?>">
                        <input type="radio" name="pay_method" value="<?=$pay_method['value']?>">
                        <span class="pay-method-label"><?=$pay_method['label']?></span>
                    </label>
                <?php endforeach;?>
            </div>

            <!-- E: 결제수단 -->

            <div class="pay-button-wrap">

                <button type="submit" class="cart-btn large submit" data-button="submit-order"><?=$this->site->config('shop_pay_test')=='Y'?'테스트 결제':'결제하기'?></button>

            </div>

        </div>
        <!-- E: 결제정보-->

    </div>
    <!-- E: 좌/우 나누기 -->

    <?=$form_close?>
</div>

<script>
    $(function() {
        // 결제수단의 첫번째 항목에 기본 체크되어있도록 설정
        $('[name="pay_method"]').eq(0).prop('checked', true);
    })
</script>