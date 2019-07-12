<div class="container">
    <div class="ax-button-group">
        <div class="left">
            <h2><?=$board['brd_title']?></h2>
        </div>
    </div>
    <div class="grid">
        <table>
            <thead>
            <tr>
                <th><input type="checkbox" data-checkbox="post" data-checkbox-all="true"></th>
                <th>#</th>
                <th class="col-xs-6">제목</th>
                <th>작성자</th>
                <th>작성일</th>
                <th>조회수</th>
                <?php if($board['brd_use_assign'] == 'Y'):?>
                    <th>승인상태</th>
                <?php endif;?>
            </tr>
            </thead>
            <tbody>
            <?php foreach($list['list'] as $row):?>
                <tr>
                    <td class="text-center"><input type="checkbox" data-checkbox="post" name="post_idx[]" value="<?=$row['post_idx']?>"></td>
                    <td class="text-center"><?=number_format($row['nums'])?></td>
                    <td>
                        <?php if(strlen($row['post_reply']) >0) :?>
                            <span style="display:inline-block;width:<?=((strlen($row['post_reply'])-1) * 16)?>px"></span>
                            <img src="<?=base_url('assets/images/common/icon_reply.gif')?>">
                        <?php endif;?>
                        <a href="<?=base_url("admin/board/read/{$board['brd_key']}/{$row['post_idx']}/?").http_build_query($this->input->get()) ?>"><?=$row['post_title']?></a>
                        <?php if($row['is_new']) :?><label class="label label-warning ML10">NEW</label><?php endif;?>
                        <?php if($row['is_hot']) :?><label class="label label-danger ML10">HIT</label><?php endif;?>
                        <?php if($row['post_count_comment']>0) :?><small>(<?=$row['post_count_comment']?>)</small><?php endif;?>
                        <?php if($row['is_secret']) :?><i class="far fa-lock"></i><?php endif;?>
                    </td>
                    <td class="text-center"><?=$row['mem_nickname']?></td>
                    <td class="text-center"><?=$row['post_regtime']?></td>
                    <td class="text-center"><?=number_format($row['post_hit'])?></td>
                    <?php if($board['brd_use_assign'] == 'Y'):?>
                        <td class="text-center">
                            <?php if($row['post_assign'] == 'Y'):?>
                                <label class="label label-success">승인</label>
                            <?php else :?>
                                <label class="label label-default">미승인</label>
                            <?php endif;?>
                        </td>
                    <?php endif;?>
                </tr>
            <?php endforeach;?>
            <?php if(count($list['list']) <= 0) :?>
                <tr>
                    <td colspan="10" class="empty">검색된 글이 없습니다.</td>
                </tr>
            <?php endif;?>
            </tbody>
        </table>
    </div>
    <div class="MT10">

        <div class="pull-right">

        </div>
        <div class="clearfix"></div>
    </div>
    <div class="MT10">
        <button type="button" class="btn btn-danger" data-button="btn-remove-posts"><i class="far fa-trash"></i> 선택 삭제</button>
        <div class="pull-right">
            <a class="btn btn-primary" href="<?=base_url("admin/board/write/{$board['brd_key']}")?>"><i class="far fa-pencil"></i> 새 글 작성</a>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<script>
    $('[data-button="btn-remove-posts"]').click(function(e){
        e.preventDefault();

        if( $('input[name="post_idx[]"]:checked').length <= 0 )
        {
            alert('삭제할 게시물을 선택해주세요');
            return;
        }

        if(! confirm( '선택한 ' + $('input[name="post_idx[]"]:checked').length + '개의 게시물을 삭제하시겠습니까?' ))
            return;

        var arr = [];
        $('input[name="post_idx[]"]:checked').each(function(){
            arr.push( $(this).val() );
        });

        $.ajax({
            url : '/ajax/board/posts',
            type : 'DELETE',
            data : {
                post_idx :arr
            },
            success:function(){
                location.reload();
            }
        })

    });
</script>