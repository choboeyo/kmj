<div class="products-review-container">
    <h4 class="product-info-title">상품리뷰 (<?=number_format($totalCount)?>건)</h4>
    <form data-form="item-review-list">
        <div class="review-search-box">
            <label class="review-order-type">
                <input type="radio" name="sort_type" value="score" <?=$sort_type==='score'?'checked':''?>>
                <span>평점순</span>
            </label>
            <label class="review-order-type">
                <input type="radio" name="sort_type" value="regtime" <?=$sort_type==='regtime'?'checked':''?>>
                <span>최신순</span>
            </label>

            <select class="review-score-filter" name="score_filter">
                <option value="">모든 별점 보기</option>
                <?php foreach($score_list as $score=>$cnt):?>
                    <option value="<?=$score?>" <?=$score==$score_filter?'selected':''?>>
                        <?php for($i=0; $i<$score; $i++):?>★<?php endfor;?><?php for($i=$score; $i<5; $i++):?>☆<?php endfor;?>
                        (<?=number_format($cnt)?>)
                    </option>
                <?php endforeach;?>
            </select>
        </div>
    </form>


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
                <?php if(count($review['buy_option'])):?>
                <ul class="order-items">
                    <?php foreach($review['buy_option'] as $opt):?>
                        <?php if(! empty($opt)):?>
                        <li><?php echo $opt;?></li>
                        <?php endif;?>
                    <?php endforeach;?>
                </ul>
                <?php endif;?>
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

    <div class="review-pagination" data-container="review-pagination"><?=$pagination?></div>

    <?php if($review_auth):?>
        <div class="review-button-row">
            <button type="button" class="btn-review-write" data-button="review-write"><i class="fas fa-pencil"></i> 리뷰 작성하기</button>
        </div>
    <?php endif;?>

    <div data-container="item-review-write"></div>
</div>

<script>
    $(function() {
        $('[name="sort_type"], [name="score_filter"]').change(function() {
            APP.SHOP.getReviewList('<?=$prd_idx?>',1);
        });

        $('[data-button="review-image-zoom"]').magnificPopup({
            type: 'image',
            closeOnContentClick: true,
            image: {
                verticalFit: false
            }
        });
    })
</script>
