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
                <div data-ax-td-wrap><p class="form-control-static"><?=$view['post_title']?></p></div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td>
                <div data-ax-td-label>작성자</div>
                <div data-ax-td-wrap><p class="form-control-static"><?=$view['mem_nickname']?></p></div>
            </div>
            <div data-ax-td>
                <div data-ax-td-label>작성시간</div>
                <div data-ax-td-wrap><p class="form-control-static"><?=$view['post_regtime']?></p></div>
            </div>
            <div data-ax-td>
                <div data-ax-td-label>조회수</div>
                <div data-ax-td-wrap><p class="form-control-static"><?=number_format($view['post_hit'])?></p></div>
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
                        <ul class="list-group" style="margin-bottom:0px;">
                            <?php foreach($view['file'] as $f) :?>
                                <li class="list-group-item"><a href="<?=$f['link']?>"><i class="far fa-download"></i> <?=$f['att_origin']?> (<?=format_size($f['att_filesize'])?>)</a></li>
                            <?php endforeach;?>
                        </ul>
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
                                <img src="<?=base_url($f['att_filename'])?>" alt="<?=$f['att_origin']?>">
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
        <a href="<?=base_url("admin/board/posts/{$board['brd_key']}/?".http_build_query($this->input->get()))?>" class="btn btn-default">목록</a>
        <?php if( $board['brd_use_reply'] == 'Y' ):?>
            <a href="<?=base_url("admin/board/write/{$board['brd_key']}/?post_parent={$view['post_idx']}")?>" class="btn btn-default">답글</a>
        <?php endif;?>
        <a href="<?=base_url("admin/board/write/{$board['brd_key']}/{$view['post_idx']}")?>" class="btn btn-default"><i class="far fa-pencil"></i> 수정</a>
        <button type="button" class="btn btn-danger" data-button="btn-remove-posts"><i class="far fa-trash"></i> 선택 삭제</button>
        <?php if( $board['brd_use_assign'] == 'Y' ):?>
            <?php if($view['post_assign'] == 'Y') :?>
                <button type="button" class="btn btn-default" data-button="btn-assign" data-value="N"><i class="far fa-ban"></i> 승인 취소</button>
            <?php else :?>
                <button type="button" class="btn btn-default" data-button="btn-assign" data-value="Y"><i class="far fa-check"></i> 승인 처리</button>
            <?php endif;?>
        <?php endif;?>
    </div>
</div>

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

        $('[data-button="btn-assign"]').click(function(e){
            var value = $(this).data('value');

            var message = "현재 글을 승인 처리하시겠습니까?";
            if( value == 'N' ) message = '현재 글을 승인 취소하시겠습니까?';

            if(! confirm(message)) return;

            $.ajax({
                url : '/ajax/board/assign',
                type:'POST',
                data : {
                    post_idx : '<?=$view['post_idx']?>',
                    post_assign : value
                },
                success:function(){
                    location.reload();
                }
            })
        });
    });
</script>