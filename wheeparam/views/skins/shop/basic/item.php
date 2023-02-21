<?php
// 이미지 확대 기능에 필요한 jquey plugin 로드
$this->site->add_js('https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js');
$this->site->add_css('https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css');
?>

<div class="skin-shop-basic">
    <!--S: 상품 카테고리 네비게이션 -->
    <div class="product-category-breadcrumb">
        <ol>
            <?php for($i=count($view['categoryArray'])-1; $i>=0; $i--) :?>
                <li><a href="<?=base_url('products/category/'.$view['categoryArray'][$i]['id'])?>"><?=$view['categoryArray'][$i]['name']?></a></li>
            <?php endfor;?>
        </ol>
    </div>
    <!--E: 상품 카테고리 네비게이션 -->

    <!--S: 상품 정보 -->
    <div class="product-info-wrap">

        <!-- S: 상품 이미지 슬라이드 -->
        <div class="product-images-wrap">

            <?php if(count($view['images']) > 0) :?>
            <a href="<?=base_url($view['images'][0]['att_filepath'])?>" class="btn-image-zoom" data-button="product-image-zoom">
                <span class="sr-only">이미지 크게보기</span>
                <i class="fa-light fa-magnifying-glass-plus"></i>
            </a>
            <?php endif;?>

            <ul class="product-images" data-container="product-big-images">
                <?php if(count($view['images']) > 0) :?>
                    <?php foreach($view['images'] as $i=>$image) :?>
                        <li class="<?=$i===0?'visible':''?>">
                            <figure class="thumbnail">
                                <img src="<?=base_url($image['att_filepath'])?>">
                            </figure>
                        </li>
                    <?php endforeach;?>
                <?php else :?>
                <li>
                    <figure class="thumbnail">
                        <img src="http://placehold.it/800x800?text=NO+IMAGE">
                    </figure>
                </li>
                <?php endif;?>
            </ul>
            <ul class="product-small-images" data-container="product-small-images">
                <?php foreach($view['images'] as $i=>$image) :?>
                    <li class="<?=$i===0?'visible':''?>">
                        <figure class="thumbnail">
                            <img src="<?=thumbnail($image['att_filepath'],200)?>">
                        </figure>
                    </li>
                <?php endforeach;?>
            </ul>
        </div>
        <!-- E: 상품 이미지 슬라이드 -->

        <!-- S: 상품 정보 표기 -->
        <div class="product-info">
            <!-- 상품명 -->
            <h4 class="product-name"><?=$view['prd_name']?></h4>

            <!-- 상품 라벨 -->
            <div class="label-wrap">
                <?php if($view['prd_is_best'] === 'Y'):?><label class="label label-best MR5">BEST</label><?php endif;?>
                <?php if($view['prd_is_hit'] === 'Y'):?><label class="label label-hit MR5">HIT</label><?php endif;?>
                <?php if($view['prd_is_new'] === 'Y'):?><label class="label label-new MR5">NEW</label><?php endif;?>
                <?php if($view['prd_is_recommend'] === 'Y'):?><label class="label label-recommend MR5">MD추천</label><?php endif;?>
                <?php if($view['prd_is_sale'] === 'Y'):?><label class="label label-sale MR5">할인</label><?php endif;?>
            </div>

            <!-- 리뷰점수/리뷰개수 -->
            <div class="product-review-info">
                <span class="rating-container">
                    <span class="rating-value" style="width:<?=$view['prd_review_average']*20?>%"></span>
                </span>
                <dl class="review-count">
                    <dt class="sr-only">리뷰 건수 :</dt>
                    <dd><?=number_format($view['prd_review_count'])?>건의 상품 리뷰</dd>
                </dl>
            </div>

            <!-- 상품 가격 표시 -->
            <div class="product-summary">
                <div class="product-price-info">
                    <?php if($view['prd_cust_price'] > 0) :?>
                        <div class="cust-price">
                            <dl>
                                <dt class="sr-only">시중가</dt>
                                <dd class="price-won"><?=number_format($view['prd_cust_price'])?>원</dd>
                            </dl>
                            <dl>
                                <dt class="sr-only">할인율</dt>
                                <dd>(<?=$view['cust_price_rate']?>)</dd>
                            </dl>
                        </div>
                    <?php endif;?>
                    <div class="sale-price">
                        <dl>
                            <dt class="sr-only">판매가</dt>
                            <dd><?=number_format($view['prd_price'])?>원</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <!-- E: 상품 정보 표기 -->

            <!-- S: 상품 요약 정보 표기 -->
            <div class="product-summary"><?=$view['prd_summary']?></div>

            <div class="H15"></div>

            <?=$form_open;?>
            <div class="product-buy-form">
                <?php if(count($options)> 0) :?>
                <!--S: 필수 선택옵션이 있는경우-->
                    <h4 class="sec-title">필수옵션 선택</h4>
                    <!-- S: 상품 옵션 선택폼 시작 -->
                    <?php foreach($options as $key=>$row) :?>
                        <div class="option-select">
                            <label class="sr-only" for="it_option_<?=$key?>"><?=$row['title']?></label>
                            <select class="qbf-select" id="it_option_<?=$key?>" data-product-cart="<?=$key?>" data-label="<?=$row['title']?>">
                                <option value=""><?=$row['title']?></option>
                                <?php foreach($row['items'] as $opt):?>
                                    <option value="<?=$opt['value']?>" data-name="<?=$opt['code']?>" data-chained="<?=$opt['parent']?>" data-price="<?=$opt['price']?>" data-stock="<?=$opt['stock']?>"><?=$opt['code']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    <?php endforeach;?>
                    <!-- E: 상품 옵션 선택폼 종료 -->
                <?php endif;?>
                <!-- E: 필수 선택옵션이 있는 경우 -->
                
                <!-- S: 추가 선택옵션이 있는 경우 -->
                <?php if(count($view['options2']) >0) :?>
                <div class="H30"></div>
                <h4 class="sec-title">추가옵션 선택</h4>

                <div class="option-select">
                    <label class="sr-only" for="it_option2">추가옵션</label>
                    <select class="qbf-select" id="it_option2_0" data-product-additional>
                        <option value="">추가옵션 선택</option>
                        <?php foreach($view['options2'] as $opt2):?>
                        <option value="<?=$opt2['opt_code']?>" data-name="<?=$opt2['opt_code']?>" data-price="<?=$opt2['opt_add_price']?>" data-stock="<?=$opt2['opt_stock_qty']?>"><?=$opt2['opt_code']?> (<?=$opt2['opt_add_price']>0?'+':''?><?=$opt2['opt_add_price']?>원)</option>
                        <?php endforeach;?>
                    </select>
                </div>

                <?php endif;?>
                <!-- E: 추가 선택옵션이 있는 경우 -->

                <!-- S: 선택한 옵션을 담는 컨테이너 -->
                <ul class="selected-options" data-container="selected-options"></ul>
                <!-- E: 선택한 옵션을 담는 컨테이너 -->

                <!-- S:옵션 선택하면 추가되는 항목의 템플릿-->
                <script data-template="option" type="text/x-jquery-tmpl">
                    <li class="selected-option-item" data-item="option">
                        <input type="hidden" name="opt_code[<?=$view['prd_idx']?>][]" value="${opt_code}">
                        <input type="hidden" name="opt_type[<?=$view['prd_idx']?>][]" value="${opt_type}">
                        <input type="hidden" name="opt_value[<?=$view['prd_idx']?>][]" value="${opt_value}">
                        <input type="hidden" name="opt_price" value="${opt_price}">
                        <input type="hidden" name="opt_stock" value="${opt_stock}">

                        <div class="opt-name">${opt_name}</div>
                        <div class="opt-count">
                            <button type="button" class="btn-qty minus" data-button="qty-minus"><i class="fas fa-minus"></i></button>
                            <input class="input-qty" data-input="cart-qty" data-number-only name="cart_qty[<?=$view['prd_idx']?>][]" value="1">
                            <button type="button" class="btn-qty plus" data-button="qty-plus"><i class="fas fa-plus"></i></button>
                            {{if opt_code != ""}}
                            <dl class="add-price">
                                <dt class="sr-only">추가금액</dt>
                                <dd>+${opt_price.numberFormat()}원</dd>
                            </dl>
                            <button type="button" class="btn-remove-qty" data-button="btn-remove-qty"><i class="fas fa-times"></i></button>
                            {{/if}}
                        </div>
                    </li>
                    </script>
                <!-- E:옵션 선택하면 추가되는 항목의 템플릿-->
                
                <dl class="total-price-row">
                    <dt>총 금액</dt>
                    <dd><strong data-container="total-price">0</strong> 원</dd>
                </dl>

                <div class="buy-button-wrap">
                    <button class="buy-button" type="button" data-button="buy-item" data-direct="N">장바구니</button>
                    <button class="buy-button primary" type="button" data-button="buy-item" data-direct="Y">바로구매</button>
                    <?php if($this->member->is_login()) :?>
                    <button class="buy-button wish <?=$view['is_wish']?'wished':''?>" type="button" onclick="APP.SHOP.toggleWish('<?=$view['prd_idx']?>')" title="<?=$view['is_wish']?'찜하기 취소':'찜하기'?>"><i class="<?=$view['is_wish']?'fas':'fal'?> fa-heart"></i></button>
                    <?php else :?>
                    <button class="buy-button wish" type="button" title="찜하기" onclick="alert('회원 로그인후 찜하기를 사용하실 수 있습니다.');"><i class="fal fa-heart"></i></button>
                    <?php endif;?>
                </div>
            </div>
            <?=$form_close;?>
        </div>
    </div>
    <!--E: 상품 정보 -->
    
    <!-- S: 상품 표기 탭 -->
    <ul class="product-info-tab">
        <li><a href="#product-info">상품 정보</a></li>
        <li><a href="#product-reviews">상품리뷰 (<?=number_format($view['prd_review_count'])?>)</a></li>
        <li><a href="#product-qna">상품문의</a></li>
        <li><a href="#product-dex">배송/교환</a></li>
    </ul>
    <!-- E: 상품 표기 탭 -->

    <!-- S: 상품 상세정보 -->
    <div class="product-section" id="product-info">
        <!-- S: 필수 표기 정보 -->
        <h4 class="product-info-title">필수 표기정보</h4>

        <div class="extra-info-wrap">
            <?php foreach($view['extra_info'] as $info) :?>
                <dl class="extra-info">
                    <dt class="extra-title"><?=$info['key']?></dt>
                    <dd class="extra-content"><?=$info['content']?></dd>
                </dl>
            <?php endforeach;?>
        </div>
        <!-- E: 필수 표기 정보 -->

        <div class="H30"></div>

        <!-- S: 상품 상세 정보 -->
        <h4 class="product-info-title">상품 상세정보</h4>
        
        <div class="product-content-html">
            <?php
            if($this->site->viewmode === DEVICE_MOBILE) :
                echo display_html_content($view['prd_mobile_content']);
            elseif($this->site->viewmode === DEVICE_DESKTOP) :
                echo display_html_content($view['prd_content']);
            endif;
            ?>
        </div>
        <!-- E: 상품 상세 정보 -->
    </div>
    <!-- E: 상품 상세정보 -->

    <div class="H30"></div>

    <!-- S: 상품 리뷰 -->
    <div class="product-section" id="product-reviews" data-prd-idx="<?=$view['prd_idx']?>" data-container="item-review"></div>
    <!-- E: 상품 리뷰 -->

    <div class="H30"></div>

    <!-- S: 상품 문의 -->
    <div class="product-section" id="product-qna" data-prd-idx="<?=$view['prd_idx']?>" data-container="item-qna"></div>
    <!-- E: 상품 문의 -->

    <div class="H30"></div>

    <!-- S: 배송/교환 정보 -->
    <div class="product-section" id="product-dex">
        <h4 class="product-info-title">배송/교환 정보</h4>
        <div>
            <?=display_html_content($this->site->config('shop_delivery_info'))?>
        </div>
        <div class="H30"></div>
        <h4 class="product-info-title">교환/반품 안내</h4>
        <div>
            <?=display_html_content($this->site->config('shop_refund_info'))?>
        </div>

    </div>
    <!-- E: 배송/교환 정보 -->
</div>

<!--S: ScrollSpy -->
<script>
    $(function() {
        $(window).on('scroll', function(e) {
            $('.product-info-tab li').removeClass('active');

            $('.product-section').each(function() {
                if( $(this).offset().top - $(window).scrollTop()  < 20) {
                    var id =$(this).attr('id');
                    $('.product-info-tab li a[href="#'+id+'"]')
                        .parents('li')
                        .addClass('active')
                        .siblings()
                        .removeClass('active');
                }
            })
        }).scroll();
    })
</script>
<!--E: ScrollSpy -->