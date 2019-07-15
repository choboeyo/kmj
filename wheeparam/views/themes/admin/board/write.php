<div class="ax-button-group">
    <div class="left">
        <h2><?=$board['brd_title']?> 글쓰기</h2>
    </div>
</div>

<?=$form_open?>

<?=validation_errors('<p class="alert alert-danger">')?>

<div data-ax-tbl>
    <?php if($use_category) :?>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>카테고리</div>
            <div data-ax-td-wrap>
                <select class="form-control" name="post_category">
                    <?php foreach($board['category'] as $cate):?>
                    <option value="<?=$cate?>" <?=$cate==element('post_category', $view)?'selected':''?>><?=$cate?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
    </div>
    <?php endif;?>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>제목</div>
            <div data-ax-td-wrap>
                <input class="form-control" id="w_title" name="post_title" value="<?=element('post_title', $view)?>" required>
            </div>
        </div>
    </div>
    <?php if(! defined('IS_REPLY_WRITE_FORM') && $use_notice) :?>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>공지</div>
            <div data-ax-td-wrap>
                <label class="w-check">
                    <input type="checkbox" value="Y" name="post_notice" <?=element('post_notice',$view,'N')=='Y'?'checked':''?>><span>공지로 등록</span>
                </label>
            </div>
        </div>
    </div>
    <?php endif;?>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>내용</div>
            <div data-ax-td-wrap>
                <?=get_editor('post_content', element('post_content', $view), '');?>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>태그</div>
            <div data-ax-td-wrap>
                <input class="form-control" id="w_keywords" name="post_keywords" value="<?=element('post_keywords', $view)?>">
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>파일첨부</div>
            <div data-ax-td-wrap>
                [widget name="board_file_upload"]
            </div>
        </div>
    </div>
    <?php if( $post_idx && count(element('file', $view, array())) > 0) :?>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>첨부된 파일</div>
            <div data-ax-td-wrap>
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
    </div>
    <?php endif;?>
</div>
<div class="text-center MT15">
    <button class="btn btn-primary">작성하기</button>
</div>
<?=$form_close?>
