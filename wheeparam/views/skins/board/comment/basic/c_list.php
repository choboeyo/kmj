<article class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">댓글</h1>
    </div>

    <div class="panel-body">
        <ul class="media-list comment-list">
            <?php foreach($comment_list['list'] as $cmt) :?>
                <li class="media comment-depth-<?=strlen($cmt['cmt_reply'])?>" id="comment-<?=$cmt['cmt_idx']?>">

                    <!-- START :: 대댓글일 경우-->
                    <?php if(strlen($cmt['cmt_reply']) > 0) : ?>
                        <div class="media-left">
                            <div class="W<?=strlen($cmt['cmt_reply'])*50?> text-center">
                                <i class="fa fa-reply"></i>
                            </div>
                        </div>
                    <?php endif;?>
                    <!-- END :: 대댓글일 경우-->

                    <div class="media-body" style="padding-left:<?=strlen($cmt['cmt_reply'])*50?>px">
                        <h4><?=$cmt['mem_nickname']?> <small><?=$cmt['cmt_datetime']?></small></h4>
                        <div data-container="container-comment-content">
                            <?php
                            if( $cmt['cmt_status'] == 'Y' ) :
                                echo display_html_content($cmt['cmt_content'],600);
                            elseif ( $cmt['cmt_status'] == 'B' ):
                                echo '<p class="alert alert-danger">관리자에 의해서 블라인드 된 댓글입니다.</p>';
                            else :
                                echo '<p class="alert alert-danger">삭제된 댓글입니다.</p>';
                            endif;
                            ?>
                        </div>
                        <div class="text-right">

                            <?php if($board['auth']['comment']) : ?>
                                <button type="button" class="btn btn-default btn-xs" onclick="APP.BOARD.COMMENT.reply('<?=$cmt['cmt_idx']?>','<?= $cmt['cmt_num']?>')">대댓글</button>
                            <?php endif;?>

                            <?php if($cmt['auth']) :?>
                                <button type="button" class="btn btn-default btn-xs" onclick="APP.BOARD.COMMENT.modify('<?=$cmt['cmt_idx']?>')">수정</button>
                                <a class="btn btn-danger btn-xs" onclick="return confirm('댓글을 삭제하시겠습니까?');" href="<?=$cmt['link']['delete']?>">삭제</a>
                            <?php endif;?>
                        </div>
                    </div>
                    <div class="comment-reply-form">
                        <?=$cmt['comment_form']?>
                    </div>
                </li>
            <?php endforeach;?>
        </ul>
        <?php if( count($comment_list['list']) <= 0 ) :?>
            <!-- START:: 등록된 댓글이 없을경우-->
            <p class="well">작성된 댓글이 없습니다</p>
            <!-- END:: 등록된 댓글이 없을경우-->
        <?php endif;?>
    </div>

</article>