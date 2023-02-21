<div class="products-qna-container">
    <h4 class="product-info-title">상품문의 (<?=number_format($totalCount)?>건)</h4>
    <?php if(count($list) === 0) :?>
        <p class="qna-empty">등록된 상품문의가 없습니다.</p>

    <?php else :?>
        <ul class="product-qna-list-wrap">
            <?php foreach($list as $qna): ?>
                <li class="qna-item">
                    <div class="qna-inner">
                        <div class="qna-writer">
                            <dl class="w-name">
                                <dt class="sr-only">작성자</dt>
                                <dd><?=$qna['nickname']?></dd>
                            </dl>
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

    <div class="qna-pagination" data-container="qna-pagination"><?=$pagination?></div>

    <?php if($this->member->is_login()):?>
        <div class="qna-button-row">
            <button type="button" class="btn-qna-write" data-button="qna-write"><i class="fas fa-pencil"></i> 문의 작성하기</button>
        </div>
    <?php endif;?>

    <div data-container="item-qna-write"></div>
</div>