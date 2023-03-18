<div class="skin-members-basic">

    <div class="container products-qna-container">
        <?=$asides_member?>

        <h2 class="members-title">내가 상품 문의 (<?=number_format($totalCount)?>)</h2>

        <?php if(count($list) === 0) :?>
            <p class="review-empty">등록된 상품문의가 없습니다.</p>
        <?php else :?>
            <ul class="product-qna-list-wrap">
                <?php foreach($list as $qna): ?>
                    <li class="qna-item">
                        <div class="qna-inner">
                            <h4 class="product-name">
                                <figure class="thumb">
                                    <?php if($qna['thumbnail']):?>
                                        <img src="<?=thumbnail($qna['thumbnail'],80)?>" alt="<?=$qna['prd_name']?>">
                                    <?php else :?>
                                        <img src="http://placehold.it/80x80?text=NO+IMAGE" alt="NO IMAGE">
                                    <?php endif;?>
                                </figure>
                                <a href="<?=base_url('products/items/'.$qna['prd_idx'])?>"><?=$qna['prd_name']?></a>
                            </h4>
                            <div class="qna-writer">
                                <dl class="w-name">
                                    <dt class="sr-only">작성자</dt>
                                    <dd><?=$qna['nickname']?></dd>
                                </dl>
                                <button type="button" data-button="delete-qna" data-idx="<?=$qna['qa_idx']?>" class="btn-skin-danger"><i class="fas fa-trash"></i></button>
                                <dl class="w-regtime">
                                    <dt class="sr-only">작성일시</dt>
                                    <dd><?=date('Y.m.d',strtotime($qna['reg_datetime']))?></dd>
                                </dl>
                            </div>
                            <div class="qna-content">
                                <label class="qna-label">질문</label>
                                <?php if($qna['is_secret']):?>
                                    <p class="qna-content-secret"><i class="fas fa-lock"></i> 작성자만 볼수 있는 글입니다.</p>
                                <?php else :?>
                                    <?=nl2br($qna['qa_content'])?>
                                <?php endif;?>
                            </div>
                            <?php if($qna['qa_is_answer'] === 'Y') :?>
                                <div class="qna-answer">
                                    <label class="qna-label answer">답변</label>
                                    <div class="qna-answer-header">
                                        <dl class="r-name">
                                            <dt class="sr-only">답변자</dt>
                                            <dd><?=$qna['a_nickname']?></dd>
                                        </dl>
                                        <dl class="r-regtime">
                                            <dt class="sr-only">답변일시</dt>
                                            <dd><?=date('Y.m.d',strtotime($qna['qa_a_datetime']))?></dd>
                                        </dl>
                                    </div>
                                    <?php if($qna['is_secret']):?>
                                        <p class="qna-content-secret"><i class="fas fa-lock"></i> 작성자만 볼수 있는 글입니다.</p>
                                    <?php else :?>
                                        <?=nl2br($qna['qa_a_content'])?>
                                    <?php endif;?>
                                </div>
                            <?php endif;?>
                        </div>
                    </li>
                <?php endforeach;?>
            </ul>
        <?php endif;?>

        <div class="qna-pagination"><?=$pagination?></div>

    </div>
</div>
        