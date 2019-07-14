<div class="container">

    <div class="page-header">
        <h2 class="page-title"><?=$board['brd_title']?></h2>
    </div>

    <article class="panel panel-default">
        <header class="panel-heading">
            <h1 class="panel-title"><?=$view['post_title']?></h1>
        </header>
        <div class="panel-body">
            <ul class="post-info">
                <dl>
                    <dt>작성자</dt>
                    <dd><?=$view['mem_nickname']?></dd>
                </dl>
                <dl>
                    <dt>조회수</dt>
                    <dd><?=$view['post_hit']?></dd>
                </dl>
                <dl>
                    <dt>작성일</dt>
                    <dd><?=$view['post_datetime']?></dd>
                </dl>
            </ul>
        </div>

        <!-- START:: 글내용-->
        <div class="panel-body">
            <?php if( $view['post_status'] == 'Y' ) :
                echo display_html_content($view['post_content']);
            else : ?>
            <p class="alert alert-danger">해당 글은 블라인드 처리된 글입니다.</p>
            <?php endif;?>

            <div class="sns-share-wrap">
                <ul class="sns-share-list">
                    <li><a href="javascript:;" data-toggle="sns-share" data-service="facebook" data-url="<?=current_url()?>" data-title="<?=$this->site->meta_title?>">페이스북 공유하기</a></li>
                    <li><a href="javascript:;" data-toggle="sns-share" data-service="google" data-url="<?=current_url()?>" data-title="<?=$this->site->meta_title?>">구글+ 공유하기</a></li>
                    <li><a href="javascript:;" data-toggle="sns-share" data-service="pinterest" data-url="<?=current_url()?>" data-title="<?=$this->site->meta_title?>">핀터레스트 공유하기</a></li>
                    <li><a href="javascript:;" data-toggle="sns-share" data-service="kakaostory" data-url="<?=current_url()?>" data-title="<?=$this->site->meta_title?>">카카오 스토리 공유하기</a></li>
                    <li><a href="javascript:;" data-toggle="sns-share" data-service="band" data-url="<?=current_url()?>" data-title="<?=$this->site->meta_title?>">밴드 공유하기</a></li>
                    <li><a href="javascript:;" data-toggle="sns-share" data-service="naver" data-url="<?=current_url()?>" data-title="<?=$this->site->meta_title?>">네이버 공유하기</a></li>
                    <li><a href="javascript:;" data-toggle="sns-share" data-service="line" data-url="<?=current_url()?>" data-title="<?=$this->site->meta_title?>">네이버 라인 공유하기</a></li>
                    <li><a href="javascript:;" data-toggle="sns-share" data-service="link" data-url="<?=current_url()?>" data-title="<?=$this->site->meta_title?>">현재 링크 복사하기</a></li>
                </ul>
            </div>
        </div>
        <!-- END:: 글내용-->

        <?php if( count($view['file']) > 0 ) :?>
        <!-- START :: 첨부파일 목록 -->
        <div class="panel-body">
            <h4>첨부파일</h4>
            <ul class="nav nav-pills nav-stacked">
                <?php foreach($view['file'] as $f) :?>
                <li><a href="<?=$f['link']?>"><i class="fa fa-download"></i> <?=$f['att_origin']?> (<?=format_size($f['att_filesize'])?>) <small>다운로드수 : <?=number_format($f['att_downloads'])?></small></a></li>
                <?php endforeach;?>
            </ul>
        </div>
        <!-- END :: 첨부파일 목록 -->
        <?php endif;?>

        <div class="panel-footer">
            <div class="pull-left">
                <?php if($view['prev']) :?>
                <a class="btn btn-sm btn-default" href="<?=$view['prev']['link']?>">이전글</a>
                <?php endif;?>
                <a class="btn btn-sm btn-default" href="<?=$board['link']['list']?>">목록으로</a>
                <?php if($view['next']) :?>
                <a class="btn btn-sm btn-default" href="<?=$view['next']['link']?>">다음글</a>
                <?php endif;?>
            </div>
            <div class="pull-right">
                <?php if($board['auth']['reply']) :?>
                    <a class="btn btn-sm btn-default" href="<?=$board['link']['reply']?>">답글</a>
                <?php endif;?>

                <a class="btn btn-sm btn-default" href="<?=$board['link']['modify']?>">수정</a>
                <a class="btn btn-sm btn-danger" href="<?=$board['link']['delete']?>">삭제</a>
            </div>
            <div class="clearfix"></div>
        </div>
    </article>

    <div class="H10"></div>

    <!-- START :: 코멘트 입력 폼-->
    <?=$comment_write?>
    <!-- END:: 코멘트 폼-->

    <!-- START :: 코멘트 목록 -->
    <?=$comment_list?>
    <!-- END:: 코멘트 폼-->

    <div class="H10"></div>

    <!-- START :: 게시글 목록-->
    <table class="table table-striped">
        <thead>
        <tr>
            <th class="text-center">#</th>
            <?php if($use_category) :?>
                <th class="text-center">분류</th>
            <?php endif;?>
            <th class="text-center">제 목</th>
            <th class="text-center">작성자</th>
            <th class="text-center">조회수</th>
            <th class="text-center">작성일</th>
        </tr>
        </thead>
        <tbody>
        <!-- START:: 글 목록-->
        <?php foreach($list['list'] as $post) :?>
            <tr>
                <td class="text-center">
                    <?php if($view['post_idx'] == $post['post_idx']) :?>
                        ▶
                    <?php elseif($post['post_notice']) : ?>
                        <label class="label label-danger">공지</label>
                    <?php elseif(strlen($post['post_reply']) > 0) : ?>

                    <?php else :
                        echo $post['nums'];
                    endif;?>
                </td>
                <?php if($use_category) :?>
                <td class="text-center"><?=$post['bca_name']?></td>
                <?php endif;?>
                <td>
                    <?php if(strlen($post['post_reply']) >0) :?>
                        <span style="display:inline-block;width:<?=((strlen($post['post_reply'])-1) * 16)?>px"></span>
                        <img src="<?=base_url('assets/images/common/icon_reply.gif')?>">
                    <?php endif;?>
                    <a href="<?=$post['link']?>"><?=$post['post_title']?></a> <!-- 제목-->
                    <?php if($post['is_new']) :?><label class="label label-danger label-sm">NEW</label><?php endif;?>
                    <?php if($post['is_hot']) :?><label class="label label-warning label-sm">HIT</label><?php endif;?>
                    <?php if($post['post_count_comment']>0) :?><small>(<?=$post['post_count_comment']?>)</small><?php endif;?>
                    <?php if($post['is_secret']) :?><i class="fa fa-lock"></i><?php endif;?>
                </td>
                <td class="text-center"><?=$post['mem_nickname']?></td>
                <td class="text-center"><?=$post['post_hit']?></td>
                <td class="text-center"><?=$post['post_datetime']?></td>
            </tr>
        <?php endforeach;?>
        <!-- END :: 글 목록-->

        <!-- START:: 등록된 글이 없는 경우-->
        <?php if(count($list['list'])==0):?>
            <tr>
                <td colspan="5" class="text-center">등록된 글이 없습니다.</td>
            </tr>
        <?php endif;?>
        <!-- END:: 등록된 글이 없는 경우-->
        </tbody>
    </table>

    <div class="text-center">
        <?=$pagination?>
    </div>
    <!-- END :: 게시글 목록-->
</div>