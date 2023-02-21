<div class="container" style="max-width:1000px;margin:0 auto;">
    <ul class="product-review-list-wrap">
        <?php foreach($list as $review): ?>
            <li class="review-item" <?=$review['rev_status']==='H'?'style="opacity:0.5"':''?>>
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
                            <a href="<?=base_url('products/items/'.$review['prd_idx'])?>" target="_blank"><?=$review['prd_name']?></a>
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

                    <div class="review-actions">
                        <?php if($review['rev_status'] === 'Y') :?>
                        <button type="button" class="btn btn-default" data-button="set-status" data-idx="<?=$review['rev_idx']?>" data-value="H">리뷰 감추기 (현재 노출중)</button>
                        <?php elseif ($review['rev_status'] === 'H') :?>
                        <button type="button" class="btn btn-default" data-button="set-status" data-idx="<?=$review['rev_idx']?>" data-value="Y">리뷰 노출시키기 (숨김중)</button>
                        <?php endif;?>
                        <button type="button" class="btn btn-danger ML5" data-button="set-status" data-idx="<?=$review['rev_idx']?>" data-value="N">리뷰 완전 삭제</button>
                    </div>
                </div>
            </li>
        <?php endforeach;?>
    </ul>

    <div class="text-center MT10"><?=$pagination?></div>

    <script>
        $(function() {
            $('[data-button="set-status"]').click(function(e) {
                e.preventDefault();
                var idx = $(this).data('idx');
                var status = $(this).data('value');

                console.log(idx, status);

                if(status === 'Y') message = '선택하신 리뷰를 [노출] 상태로 변경하시겠습니까?'
                else if(status === 'N') message = '선택하신 리뷰를 [완전삭제] 하시겠습니까?'
                else if (status === 'H') message = '현재 노출중인 리뷰를 [감춤] 상태로 변경하시겠습니까?'
                else return;

                if(! confirm(message)) return;

                $.ajax({
                    url: base_url + '/admin/ajax/products/review-status',
                    type: 'POST',
                    data: {
                        rev_idx: idx,
                        rev_status: status
                    },
                    success: function() {
                        location.reload();
                    }
                })
            })
        })
    </script>

    <style>
        .review-empty {height:150px; display:flex; justify-content: center; align-items: center; font-size:1rem; color:#878787;}
        .product-review-list-wrap {list-style:none; padding:0; margin:0;}
        .product-review-list-wrap .review-item {padding:1rem 0; border-bottom:1px solid #eceff3;}
        .product-review-list-wrap .review-item .review-writer {display:flex; align-items: center}
        .product-review-list-wrap .review-item .review-writer .w-name {font-size:1.2rem; font-weight:700; color:#282828; margin-right:1rem;}
        .product-review-list-wrap .review-item .review-writer .w-rating {margin-right:1rem;}
        .product-review-list-wrap .review-item .review-writer .w-regtime {margin-left:auto; color:#787878;}
        .product-review-list-wrap .review-item .product-name {font-size:1.2rem; font-weight:700; margin-bottom:0; padding-top:1rem; display:flex; padding:1rem; background:#fafafa;}
        .product-review-list-wrap .review-item .product-name .thumb {flex-shrink: 0; position:relative; width:80px; height:80px; margin-right:1rem;}
        .product-review-list-wrap .review-item .product-name .thumb img {position:absolute;top:0;left:0; width:100%; height:100%; object-fit: cover}
        .product-review-list-wrap .review-item .product-name a {color:inherit;}
        .product-review-list-wrap .review-item .product-name a:hover {text-decoration: underline;}
        .product-review-list-wrap .review-item .order-items {padding:0;margin:0;list-style:none;}
        .product-review-list-wrap .review-item .order-items li {display:block; padding:.25rem 0; color:#888; font-size:.9rem;}
        .product-review-list-wrap .review-item .review-content {padding:1rem 0;}
        .product-review-list-wrap .review-item .review-content-images {list-style:none; padding:0; margin:0 -.5rem; display:flex; flex-wrap:wrap;}
        .product-review-list-wrap .review-item .review-content-images li {width:16.666666667%; padding:.5rem;}
        .product-review-list-wrap .review-item .review-content-images li .thumb {width:100%; height:0; padding-bottom:100%; position:relative;}
        .product-review-list-wrap .review-item .review-content-images li .thumb img {display:block; width:100%; height:100%; object-fit: contain; position:absolute;top:0;left:0;}
        .review-pagination {padding:1rem 0; margin:0; width:100%;}
        .review-pagination .pagination {display:flex; justify-content: center; align-items: center; list-style:none;  padding:0; margin:0;}
        .review-pagination .pagination li {display:block;}
        .review-pagination .pagination li a,
        .review-pagination .pagination li span { padding:.325rem .75rem; display:block; color:#787878; }
        .review-pagination .pagination li.active span,
        .review-pagination .pagination li.active a { color:var(--theme-color-primary); }
        .review-pagination .pagination li.disabled span,
        .review-pagination .pagination li.disabled a { color:#c0c0c0; cursor:default; }
        .rating-container {display:block; width:75px; height:15px; background:url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyMSIgdmlld0JveD0iMCAwIDIzIDIxIj4KICAgIDxwYXRoIGZpbGw9IiNDQ0MiIGZpbGwtcnVsZT0iZXZlbm9kZCIgZD0iTTE1IDYuMzY4bDYuNjk0IDEuMTE5Yy43NTQuMTI1LjkzMi42Ni4zOTggMS4xOTdsLTQuNzQ0IDQuNzQ4Ljk4MSA2LjU2MmMuMTEuNzQtLjM1NyAxLjA3Mi0xLjA0Mi43NGwtNi4wODctMi45NC02LjA4NyAyLjk0Yy0uNjg1LjMzMi0xLjE1MiAwLTEuMDQyLS43NGwuOTgtNi41NjJMLjMwOCA4LjY4NGMtLjUzNC0uNTM2LS4zNTYtMS4wNzIuMzk4LTEuMTk3TDcuNCA2LjM2OCAxMC41NTcuNDk2Yy4zNTUtLjY2MS45MzEtLjY2MSAxLjI4NiAwTDE1IDYuMzY4eiIvPgo8L3N2Zz4K) repeat-x; background-size:15px; }
        .rating-container .rating-value {height:100%; content:'';display:block; background:url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyMSIgdmlld0JveD0iMCAwIDIzIDIxIj4KICAgIDxwYXRoIGZpbGw9IiNGRjk2MDAiIGZpbGwtcnVsZT0iZXZlbm9kZCIgZD0iTTExLjg0My40OTZMMTUgNi4zNjhsNi42OTQgMS4xMTljLjc1NC4xMjUuOTMyLjY2LjM5OCAxLjE5N2wtNC43NDQgNC43NDguOTgxIDYuNTYyYy4xMS43NC0uMzU3IDEuMDcyLTEuMDQyLjc0bC02LjA4Ny0yLjk0LTYuMDg3IDIuOTRjLS42ODUuMzMyLTEuMTUyIDAtMS4wNDItLjc0bC45OC02LjU2MkwuMzA4IDguNjg0Yy0uNTM0LS41MzYtLjM1Ni0xLjA3Mi4zOTgtMS4xOTdMNy40IDYuMzY4IDEwLjU1Ny40OTZjLjM1NS0uNjYxLjkzMS0uNjYxIDEuMjg2IDAiLz4KPC9zdmc+Cg==) repeat-x; background-size:15px; }

    </style>
</div>

