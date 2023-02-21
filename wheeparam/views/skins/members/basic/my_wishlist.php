<div class="skin-members-basic">

    <div class="container">

        <?=$asides_member?>

        <h2 class="members-title">내 찜 보관함</h2>


        <?php if(count($list) > 0 ):?>
            <ul class="-product-list">
                <?php foreach($list as $product):?>
                    <li class="-item">
                        <div class="-inner">
                            <figure class="-thumb">
                                <a class="-inner-anchor" href="<?=$product['link']?>">
                                    <?php if($product['thumbnail']):?>
                                        <img src="<?=thumbnail($product['thumbnail'],400)?>" alt="<?=$product['prd_name']?>">
                                    <?php else :?>
                                        <figcaption>NO IMAGE</figcaption>
                                    <?php endif;?>
                                </a>
                                <div class="-actions">
                                    <button type="button" class="btn-cart-quick" onclick="APP.SHOP.toggleWish('<?=$product['prd_idx']?>')"><i class="fas fa-trash"></i></button>
                                </div>
                            </figure>
                            <div class="-info">
                                <h4 class="-title"><a class="-inner-anchor" href="<?=$product['link']?>"><?=$product['prd_name']?></a></h4>
                                <p class="-summary"><?=$product['prd_summary']?></p>
                            </div>
                        </div>
                    </li>
                <?php endforeach;?>
            </ul>
        <?php else:?>
            <p class="empty-list">상품이 없습니다.</p>
        <?php endif;?>
    </div>

</div>