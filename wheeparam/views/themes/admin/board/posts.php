<div class="page-header" data-fit-aside>
    <h1 class="page-title"><?=$board['brd_title']?></h1>
</div>

<form data-grid-search onsubmit="grid.refresh(1);return false;" data-fit-aside autocomplete="off">
    <div data-ax-tbl>
        <div data-ax-tr>
            <div data-ax-td>
                <div data-ax-td-label>검색어 입력</div>
                <div data-ax-td-wrap>
                    <select class="form-control form-control-inline" name="scol">
                        <option value="title" <?=$scol=='title'?'selected':''?>>제목</option>
                        <option value="nickname" <?=$scol=='nickname'?'selected':''?>>작성자</option>
                    </select>
                </div>
            </div>

            <div data-ax-td>
                <div data-ax-td-wrap>
                    <input class="form-control" name="stxt" value="<?=$stxt?>">
                </div>
                <div data-ax-td-wrap>
                    <button class="btn btn-default btn-sm"><i class="fal fa-search"></i> 검색</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="H10"></div>

<div class="grid">
    <table>
        <thead>
        <tr>
            <th class="W20 text-center"><label class="w-check margin-auto"><input type="checkbox" data-checkbox="post" data-checkbox-all="true"><span class="empty"></span></label></th>
            <th class="W60">#</th>
            <?php if($use_category) :?>
            <th class="W120">카테고리</th>
            <?php endif;?>
            <th>제목</th>
            <th class="W120">작성자</th>
            <th class="W120">작성일</th>
            <th class="W80">조회수</th>
            <th class="W60">모바일</th>
            <th class="W100">작성 IP</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($list['list'] as $row):?>
            <tr>
                <td class="text-center">
                    <label class="w-check margin-auto"><input type="checkbox" data-checkbox="post" name="post_idx[]" value="<?=$row['post_idx']?>"><span class="empty"></span></label>
                </td>
                <?php if($row['post_notice'] == 'Y') :?>
                <td class="text-center"><label class="label">공지</label></td>
                <?php else :?>
                <td class="text-right"><?=number_format($row['nums'])?></td>
                <?php endif;?>
                <?php if($use_category) :?>
                <td><?=$row['post_category']?></td>
                <?php endif;?>
                <td>
                    <?php if(strlen($row['post_reply']) >0) :?>
                        <span style="display:inline-block;width:<?=((strlen($row['post_reply'])-1) * 16)?>px"></span>
                        <img src="<?=base_url('assets/images/common/icon_reply.gif')?>">
                    <?php endif;?>
                    <a href="<?=base_url("admin/board/read/{$board['brd_key']}/{$row['post_idx']}/?").http_build_query($this->input->get()) ?>"><?=html_escape($row['post_title'])?></a>
                    <?php if($row['is_new']) :?><label class="label label-warning ML10">NEW</label><?php endif;?>
                    <?php if($row['is_hot']) :?><label class="label label-danger ML10">HIT</label><?php endif;?>
                    <?php if($row['post_count_comment']>0) :?><small>(<?=$row['post_count_comment']?>)</small><?php endif;?>
                    <?php if($row['is_secret']) :?><i class="fal fa-lock"></i><?php endif;?>
                </td>
                <td class="text-center"><?=$row['post_nickname']?></td>
                <td class="text-center"><?=$row['post_datetime']?></td>
                <td class="text-right"><?=number_format($row['post_hit'])?></td>
                <td class="text-center"><?=$row['post_mobile']?></td>
                <td class="text-center"><?=long2ip((int)$row['post_ip'])?></td>
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
<div class="text-center MT10"><?=$pagination?></div>
<div class="ax-button-group ax-button-group-bottom">
    <div class="left">
        <button type="button" class="btn btn-danger" data-button="btn-remove-posts"><i class="fal fa-trash"></i> 선택 삭제</button>
    </div>
    <div class="right">
        <a class="btn btn-primary" href="<?=base_url("admin/board/write/{$board['brd_key']}")?>"><i class="fal fa-pencil"></i> 새 글 작성</a>
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