<div class="container">
    <?=$form_open?>
    <article class="panel panel-default">
        <header class="panel-heading">
            <h1 class="panel-title"><?=$is_reply?'대댓글 입력':'댓글 수정'?></h1>
        </header>
        <div class="panel-body">
            <textarea class="form-control" name="cmt_content" rows="4" style="resize:vertical"><?=element('cmt_content', $view)?></textarea>
            <div class="H10"></div>
            <div class="form-inline">
                <?php if($is_reply):?>
                    <div class="pull-left">
                        <input class="form-control" name="cmt_nickname" placeholder="닉네임" required>
                    </div>
                <?php endif;?>
                <?php if(! $this->member->is_login()) :?>
                    <div class="pull-left">
                        <input type="password" class="form-control" name="cmt_password" placeholder="비밀번호" required>
                    </div>
                <?php endif;?>
            </div>
        </div>
        <div class="panel-footer text-right">
            <button class="btn btn-primary">댓글 수정</button>
        </div>
    </article>
    <?=$form_close?>
</div>
