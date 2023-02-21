<div class="skin-shop-list-basic">

    <div class="container">
        <?php if(count($list) > 0 ):?>
            <ul class="-product-list">
                <?php foreach($list as $product):?>
                    <li class="-item">
                        <div class="-inner">
                            <figure class="-thumb" data-container="quick-cart-<?=$product['prd_idx']?>">
                                <a class="-inner-anchor" href="<?=$product['link']?>">
                                <?php if($product['thumbnail']):?>
                                    <img src="<?=thumbnail($product['thumbnail'],400)?>" alt="<?=$product['prd_name']?>">
                                <?php else :?>
                                    <figcaption>NO IMAGE</figcaption>
                                <?php endif;?>
                                </a>
                                <div class="-actions">
                                    <button type="button" class="btn-cart-quick" onclick="APP.SHOP.viewQuickCart('<?=$product['prd_idx']?>')">장바구니</button>
                                </div>
                            </figure>
                            <div class="-info">
                                <h4 class="-title"><a class="-inner-anchor" href="<?=$product['link']?>"><?=$product['prd_name']?></a></h4>
                                <div class="-review-score">
                                    <span class="rating-container">
                                        <span class="rating-value" style="width:<?=$product['prd_review_average']*20?>%"></span>
                                    </span>
                                    <dl class="review-count">
                                        <dt class="sr-only">리뷰 건수 :</dt>
                                        <dd><?=number_format($product['prd_review_count'])?>건의 상품 리뷰</dd>
                                    </dl>
                                </div>
                                <p class="-summary"><?=$product['prd_summary']?></p>
                                <div class="price-info">
                                    <?php if($product['prd_sell_status'] === 'O') :?>
                                    <span class="text-red">일시품절</span>
                                    <?php elseif ($product['prd_sell_status']==='D'):?>
                                    <span class="text-red">일시판매중지</span>
                                    <?php else :?>
                                        <?php if($product['prd_cust_price'] > 0) :?>
                                        <div class="cust-price-row">
                                            <span class="cust-price"><?=number_format($product['prd_cust_price'])?> 원</span> (<?=$product['cust_price_rate']?>)</div>
                                        <?php endif;?>
                                        <div class="price-row"><?=number_format($product['prd_price'])?> 원</div>
                                    <?php endif;?>
                                </div>
                                
                            </div>
                        </div>
                        <div class="-buy-wrap">

                        </div>
                    </li>
                <?php endforeach;?>
            </ul>
        <?php else:?>
            <p class="empty-list">상품이 없습니다.</p>
        <?php endif;?>
    </div>

</div>