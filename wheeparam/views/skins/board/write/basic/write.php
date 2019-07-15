<div class="container">
    <div class="page-header">
        <h1 class="page-title"><?=$board['brd_title']?> <?=($post_idx?'글 수정': (defined('IS_REPLY_WRITE_FORM')?'답글 달기':'글쓰기'))?></h1>
    </div>

    <?=$form_open?>
    <div class="form-horizontal">
        <?php if(! defined('IS_REPLY_WRITE_FORM') && $use_category) :?>
        <!-- START :: 카테고리를 사용한다면 -->
        <div class="form-group">
            <label class="control-label col-sm-3 col-md-2">카테고리</label>
            <div class="col-sm-9 col-md-10">
                <select class="form-control" name="post_category">
                    <?php foreach($board['category'] as $cate):?>
                        <option value="<?=$cate?>" <?=$cate==element('post_category', $view)?'selected':''?>><?=$cate?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <!-- END :: 카테고리를 사용한다면 -->
        <?php endif;?>

        <!-- START :: 제목 -->
        <div class="form-group">
            <label class="control-label col-sm-3 col-md-2">제목</label>
            <div class="col-sm-9 col-md-10">
                <input class="form-control" name="post_title" value="<?=element('post_title', $view)?>" required>
            </div>
        </div>
        <!-- END :: 제목 -->

        <?php if(! defined('IS_REPLY_WRITE_FORM') && $use_notice) :?>
        <!-- START:: 공지사항 권한이 있다면-->
        <div class="form-group">
            <label class="control-label col-sm-3 col-md-2">공지사항</label>
            <div class="col-sm-9 col-md-10">
                <div class="checkbox">
                    <label><input type="checkbox" value="Y" name="post_notice" <?=element('post_notice',$view,'N')=='Y'?'checked':''?>> 공지사항</label>
                </div>
            </div>
        </div>
        <!-- END:: 공지사항 권한이 있다면 -->
        <?php endif;?>

        <?php if(! defined('IS_REPLY_WRITE_FORM') && $use_secret) : ?>
        <!-- START:: 비밀글 기능을 사용한다면 -->
        <div class="form-group">
            <label class="control-label col-sm-3 col-md-2">비밀글</label>
            <div class="col-sm-9 col-md-10">
                <div class="checkbox">
                    <label><input type="checkbox" value="Y" name="post_secret" <?=element('post_secret',$view,'N')=='Y'?'checked':''?>> 비밀글</label>
                </div>
            </div>
        </div>
        <!-- END:: 비밀글 기능을 사용한다면 -->
        <?php endif;?>


        <?php if( ! $this->member->is_login() ) : ?>
        <!--START :: 비회원일 경우 입력폼 추가 -->
        <div class="form-group">
            <label class="control-label col-sm-3 col-md-2">작성자</label>
            <div class="col-sm-9 col-md-10">
                <input class="form-control" name="post_nickname" value="<?=element('post_nickname', $view)?>" required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-3 col-md-2">비밀번호</label>
            <div class="col-sm-9 col-md-10">
                <input type="password" class="form-control" name="post_password" value="" required>
            </div>
        </div>
        <!--END :: 비회원일 경우 입력폼 추가 -->
        <?php endif;?>
        
        <div class="form-group">
            <label class="control-label col-sm-3 col-md-2">글 내용</label>
            <div class="col-sm-9 col-md-10">
                <?=get_editor('post_content', element('post_content', $view), '');?>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-3 col-md-2">태그</label>
            <div class="col-sm-9 col-md-10">
                <input class="form-control" name="post_keywords" value="<?=element('post_keywords', $view)?>">
            </div>
        </div>

        <!-- START :: 파일 첨부 기능을 사용하고, 권한이 있을경우 -->
        <div class="form-group">
            <label class="control-label col-sm-3 col-md-2">파일 첨부</label>
            <div class="col-sm-9 col-md-10">
                [widget name="board_file_upload"]
            </div>
        </div>
        <!-- END :: 파일 첨부 기능을 사용하고, 권한이 있을경우 -->

        <?php if( $post_idx && count(element('file', $view)) > 0) :?>
        <!-- START :: 현재 첨부되어 있는 이미지 -->
        <div class="form-group">
            <label class="control-label col-sm-3 col-md-2">첨부된 파일</label>
            <div class="col-sm-9 col-md-10">
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
                <div class="clearfix"></div>
            </div>
        </div>
        <!-- END :: 현재 첨부되어 있는 이미지-->
        <?php endif;?>

        <?php if( ! $this->member->is_login() && $this->site->config('google_recaptcha_site_key') && $this->site->config('google_recaptcha_secret_key') ) :?>
        <!-- START :: 비회원일경우 구글 reCaptcha 사용 -->
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <div class="form-group">
            <label class="control-label col-sm-3 col-md-2">자동 등록방지</label>
            <div class="col-sm-9 col-md-10">
                <div class="g-recaptcha" data-sitekey="<?=$this->site->config('google_recaptcha_site_key')?>"></div>
            </div>
        </div>
        <!-- END :: 비회원일경우 구글 reCaptcha 사용 -->
        <?php endif;?>
    </div>
    
    <div class="text-center">
        <button type="submit" class="btn btn-primary">글 작성하기</button>
    </div>
    <?=$form_close?>
</div>