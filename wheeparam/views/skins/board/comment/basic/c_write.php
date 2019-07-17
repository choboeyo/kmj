<div class="comment-write">
    <h4>댓글 입력</h4>
    <?=$comment_form_open?>
    <div class="row">
        <div class="col-xs col-4">
            <div class="form-group">
                <input class="form-control" name="cmt_nickname" placeholder="닉네임" required maxlength="20" value="<?=element('cmt_nickname',$comment_view, $this->member->info('nickname'))?>">
            </div>
        </div>
        <?php if(!$this->member->is_login()):?>
        <div class="col-xs col-4">
            <div class="form-group">
                <input type="password" class="form-control" name="cmt_password" placeholder="비밀번호" required maxlength="24">
            </div>
        </div>
        <?php endif;?>
    </div>
    <div class="form-group">
        <div class="input-group">
            <textarea class="form-control" name="cmt_content" data-textarea="autoresize" required placeholder="댓글 내용 입력"><?=element('cmt_content',$comment_view)?></textarea>
            <div class="input-group-append">
                <button class="btn btn-pink btn-comment">등록</button>
            </div>
        </div>
    </div>
    <?=$comment_form_close?>
</div>