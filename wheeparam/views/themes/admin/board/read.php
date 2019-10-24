<div class="container">
    <div class="ax-button-group">
        <div class="left">
            <h2><?=$board['brd_title']?></h2>
        </div>
    </div>
    <div data-ax-tbl>
        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>제목</div>
                <div data-ax-td-wrap><p class="form-control-static"><?=html_escape($view['post_title'])?></p></div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td>
                <div data-ax-td-label>작성자</div>
                <div data-ax-td-wrap><p class="form-control-static"><?=$view['post_nickname']?></p></div>
            </div>
            <div data-ax-td>
                <div data-ax-td-label>작성시간</div>
                <div data-ax-td-wrap><p class="form-control-static"><?=$view['reg_datetime']?></p></div>
            </div>
            <div data-ax-td>
                <div data-ax-td-label>최종수정</div>
                <div data-ax-td-wrap><p class="form-control-static"><?=$view['upd_datetime']?></p></div>
            </div>
            <div data-ax-td>
                <div data-ax-td-label>조회수</div>
                <div data-ax-td-wrap><p class="form-control-static"><?=number_format($view['post_hit'])?></p></div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td>
                <div data-ax-td-label>작성IP</div>
                <div data-ax-td-wrap><p class="form-control-static"><?=long2ip((int)$view['post_ip'])?></p></div>
            </div>

            <div data-ax-td>
                <div data-ax-td-label>모바일</div>
                <div data-ax-td-wrap><p class="form-control-static"><?=$view['post_mobile']?></p></div>
            </div>
        </div>

        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>게시글 링크</div>
                <div data-ax-td-wrap><p class="form-control-static"><a target="_blank" href="<?=base_url('board/'.$board['brd_key'].'/'.$view['post_idx'])?>"><?=base_url('board/'.$board['brd_key'].'/'.$view['post_idx'])?></a></p></div>
            </div>
        </div>

        <?php for($i=1; $i<=10; $i++):?>
            <?php if(element('post_ext'.$i, $view)) :?>
                <div data-ax-tr>
                    <div data-ax-td class="width-100">
                        <div data-ax-td-label>필드 <?=$i?></div>
                        <div data-ax-td-wrap>
                            <p class="form-control-static"><?=$view["post_ext{$i}"]?></p>
                        </div>
                    </div>
                </div>
            <?php endif;?>
        <?php endfor;?>

        <?php if( count($view['file']) > 0 ) :?>
            <div data-ax-tr>
                <div data-ax-td class="width-100">
                    <div data-ax-td-label>첨부파일</div>
                    <div data-ax-td-wrap>
                        <?php foreach($view['file'] as $f) :?>
                            <a class="btn btn-xs btn-default" href="<?=$f['link']?>"><i class="fal fa-download"></i> <?=$f['att_origin']?> (<?=format_size($f['att_filesize'])?>)</a>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
        <?php endif;?>

        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>내용</div>
                <div data-ax-td-wrap>
                    <div style="min-height:300px;line-height:1.4em">
                        <!-- S:첨부파일중 이미지 표시 -->
                        <?php foreach($view['file'] as $f) : if($f['att_is_image']!='Y') continue;?>
                            <figure style="margin-bottom:10px;">
                                <img src="<?=base_url($f['att_filepath'])?>" alt="<?=$f['att_origin']?>">
                                <figcaption class="sr-only"><?=$f['att_origin']?></figcaption>
                            </figure>
                        <?php endforeach;?>
                        <!-- E:첨부파일중 이미지 표시 -->
                        <?=display_html_content($view['post_content'])?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center MT10">
        <a href="<?=base_url("admin/board/posts/{$board['brd_key']}/?".http_build_query($this->input->get()))?>" class="btn btn-default MR5">목록</a>
        <?php if( $board['brd_use_reply'] == 'Y' ):?>
            <a href="<?=$board['link']['reply']?>" class="btn btn-default MR5">답글</a>
        <?php endif;?>
        <a href="<?=$board['link']['modify']?>" class="btn btn-default MR5"><i class="fal fa-pencil"></i> 수정</a>
        <button type="button" class="btn btn-danger" data-button="btn-remove-posts"><i class="fal fa-trash"></i> 글 삭제</button>
    </div>

    <?php if($board['brd_use_comment'] == 'Y') :?>
    <div class="H30"></div>
    <?=form_open("admin/board/comment/{$board['brd_key']}/{$view['post_idx']}",array("id"=>"form-board-comment","data-form"=>"board-comment"))?>
    <input type="hidden" name="cmt_nickname" value="<?=$this->member->info('nickname')?>">
    <div data-ax-tbl>
        <div class="caption">댓글 입력</div>
        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>내용</div>
                <div data-ax-td-wrap>
                    <textarea class="form-control" name="cmt_content" data-autosize required placeholder="댓글 내용 입력"></textarea>
                </div>
                <button class="btn btn-primary btn-comment" style="margin:7px;"><i class="fal fa-reply"></i> 댓글 등록</button>
            </div>
        </div>
    </div>
    <?=form_close()?>

    <ul class="comment-list">
        <?php foreach($comments['list'] as $cmt) :?>
        <li class="comment-depth-<?=strlen($cmt['cmt_reply'])?>">
            <h4 class=""><?=$cmt['cmt_nickname']?> <small><?=$cmt['cmt_datetime']?></small></h4>
            <div class="content" data-container="container-comment-content">
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
            <div class="comment-action MT10">
                <button type="button" class="btn btn-default btn-xs MR5" onclick="$(this).parents('li').find('.comment-reply-form').slideToggle('fast');">대댓글</button>
                <button type="button" class="btn btn-default btn-xs MR5" onclick="APP.BOARD.COMMENT.modify('<?=$cmt['cmt_idx']?>')">수정</button>
                <a class="btn btn-danger btn-xs" onclick="return confirm('댓글을 삭제하시겠습니까?');" href="<?=$cmt['link']['delete']?>">삭제</a>
            </div>
            <div class="comment-reply-form MT10" style="display:none;">
                <div class="comment-write">
                    <h4>댓글 입력</h4>
                    <?=form_open("admin/board/comment/{$board['brd_key']}/{$view['post_idx']}", array("data-form"=>"board-comment"))?>
                    <input type="hidden" name="cmt_idx" value="">
                    <input type="hidden" name="cmt_parent" value="<?=$cmt['cmt_idx']?>">
                    <input type="hidden" name="reurl" value="<?=current_full_url()?>">
                    <input type="hidden" name="cmt_nickname" value="<?=$this->member->info('nickname')?>">
                    <div class="comment-write-inputs">
                        <textarea class="form-control" name="cmt_content" data-autosize required placeholder="댓글 내용 입력"></textarea>
                        <button class="btn btn-primary W200">대댓글 등록</button>
                    </div>
                    <?=form_close()?>
                </div>
            </div>
        </li>
        <?php endforeach;?>

        <?php if( count($comments['list']) == 0 ):?>
            <p class="empty" style="padding:15px; text-align:center;">작성된 댓글이 없습니다.</p>
        <?php endif;?>
    </ul>
    <?php endif;?>
</div>

<style>
    .comment-list {margin:0; padding:0; list-style:none;}
    .comment-list li { position: relative; padding: 5px 15px; border-top: 1px solid #eceff3; background: #f5f8f9;color: #000;word-break: break-all;}
    .comment-write-inputs { display:flex;}
    .comment-list li.comment-depth-0 {padding-left:15px;}
    .comment-list li.comment-depth-1 {padding-left:50px;}
    .comment-list li.comment-depth-2 {padding-left:85px;}
    .comment-list li.comment-depth-3 {padding-left:120px;}
    .comment-list li.comment-depth-4 {padding-left:155px;}
    .comment-list li.comment-depth-5 {padding-left:190px;}
</style>

<script>
    $(function(){
        $('[data-button="btn-remove-posts"]').click(function(e){
            var arr = ['<?=$view['post_idx']?>'];

            if(! confirm( '현재글을 삭제하시겠습니까?' ))
                return;

            $.ajax({
                url : '/ajax/board/posts',
                type : 'DELETE',
                data : {
                    post_idx :arr
                },
                success:function(){
                    location.href = "<?=base_url("admin/board/posts/{$board['brd_key']}/?".http_build_query($this->input->get()))?>";
                }
            })
        });
    });
</script>