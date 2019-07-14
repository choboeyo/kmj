<div class="container">
    <div class="ax-button-group">
        <div class="left">
            <h2><?=$board['brd_title']?> 글쓰기</h2>
        </div>
    </div>

    <div class="form-flex">
        <?=$form_open?>
        <?=validation_errors('<p class="alert alert-danger">')?>
        <div class="form-group">
            <label class="control-label">제목</label>
            <div class="controls">
                <input class="form-control" id="w_title" name="post_title" value="<?=element('post_title', $view)?>" required>
            </div>
        </div>
        <?php if(! defined('IS_REPLY_WRITE_FORM') && $use_notice) :?>
            <div class="form-group">
                <label class="control-label">공지</label>
                <div class="controls">
                    <label class="w-check">
                        <input type="checkbox" value="Y" name="post_notice" <?=element('post_notice',$view,'N')=='Y'?'checked':''?>><span>공지로 등록</span>
                    </label>
                </div>
            </div>
        <?php endif;?>
        <div class="form-group">
            <label class="control-label">내용</label>
            <div class="controls">
                <?=get_editor('post_content', element('post_content', $view), '');?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">태그</label>
            <div class="controls">
                <input class="form-control" id="w_keywords" name="post_keywords" value="<?=element('post_keywords', $view)?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">파일첨부</label>
            <div class="controls">
                [widget name="board_file_upload"]
            </div>
        </div>

        <?php if( $post_idx && count(element('file', $view, array())) > 0) :?>
            <div class="form-group">
                <label class="control-label">첨부된 파일</label>
                <div class="controls">
                    <?php foreach($view['file'] as $attach) : ?>
                        <div class="col-sm-3">
                            <?php $img_url = ($attach['att_is_image'] == 'Y') ? base_url($attach['att_filepath']) : base_url('assets/images/common/attach.png');  ?>
                            <figure>
                                <img class="img-responsive" src="<?=$img_url?>" <?=($attach['att_is_image'] != 'Y')?'style="max-width:64px;margin:auto"':''?>>
                                <figcaption><?=$attach['att_origin']?></figcaption>
                            </figure>
                            <div class="checkbox">
                                <label><input type="checkbox" name="del_file[]" value="<?=$attach['att_idx']?>"> 파일 삭제</label>
                            </div>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
        <?php endif;?>
        <div class="text-center MT10">
            <button class="btn btn-primary">작성하기</button>
        </div>
        <?=$form_close?>
    </div>
</div>
