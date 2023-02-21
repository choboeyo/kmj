<div class="skin-members-basic">

    <div class="container products-review-container">
        <?=$asides_member?>

        <h2 class="members-title">내가 작성한 리뷰 (<?=number_format($totalCount)?>)</h2>

        <?php if(count($list) === 0) :?>
            <p class="review-empty">등록된 리뷰가 없습니다.</p>

        <?php else :?>
            <ul class="product-review-list-wrap">
                <?php foreach($list as $review): ?>
                    <li class="review-item">
                        <div class="review-inner">
                            <div class="review-writer">
                                <dl class="w-name">
                                    <dt class="sr-only">작성자</dt>
                                    <dd><?=$review['nickname']?></dd>
                                </dl>
                                <dl class="w-rating">
                                    <dt class="sr-only">평점</dt>
                                    <dd>
                                        <span class="rating-container"><span class="rating-value" style="width:<?=$review['rev_score']*20?>%"></span></span>
                                    </dd>
                                </dl>
                                <dl class="w-regtime">
                                    <dt class="sr-only">작성일시</dt>
                                    <dd><?=date('Y.m.d',strtotime($review['reg_datetime']))?></dd>
                                </dl>
                            </div>
                            <h4 class="product-name">
                                <figure class="thumb">
                                    <?php if($review['thumbnail']):?>
                                        <img src="<?=thumbnail($review['thumbnail'],80)?>" alt="<?=$review['prd_name']?>">
                                    <?php else :?>
                                        <img src="http://placehold.it/80x80?text=NO+IMAGE" alt="NO IMAGE">
                                    <?php endif;?>
                                </figure>
                                <div>
                                    <a href="<?=base_url('products/items/'.$review['prd_idx'])?>"><?=$review['prd_name']?></a>
                                    <ul class="order-items">
                                        <?php foreach($review['buy_option'] as $opt):?>
                                            <?php if(! empty($opt)):?>
                                                <li><?php echo $opt;?></li>
                                            <?php endif;?>
                                        <?php endforeach;?>
                                    </ul>
                                </div>

                            </h4>

                            <div class="review-content">
                                <?php if(count($review['images']) > 0) :?>
                                    <ul class="review-content-images">
                                        <?php foreach($review['images'] as $img):?>
                                            <li>
                                                <a href="<?=base_url($img['att_filepath'])?>" class="btn-image-zoom" data-button="review-image-zoom">
                                                    <figure class="thumb">
                                                        <img src="<?=thumbnail($img['att_filepath'], 100)?>">
                                                    </figure>
                                                </a>
                                            </li>
                                        <?php endforeach;?>
                                    </ul>
                                <?php endif;?>
                                <?=nl2br($review['rev_content'])?>
                            </div>
                        </div>
                    </li>
                <?php endforeach;?>
            </ul>
        <?php endif;?>

        <div class="review-pagination"><?=$pagination?></div>

    </div>
</div>
