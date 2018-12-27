<div class="page-header">
    <h1 class="page-title">게시판 관리</h1>
</div>

<div data-ax5grid>
    <table>
        <thead>
        <tr>
            <th>번호</th>
            <th>고유키</th>
            <th>게시판 이름</th>
            <th>목록 스킨</th>
            <th>페이지당 글</th>
            <th>등록된 글</th>
            <th>카테고리</th>
            <th>답글</th>
            <th>댓글</th>
            <th>목록</th>
            <th>첨부파일</th>
            <th>내용</th>
            <th>쓰기</th>
            <th>관리</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($board_list['list'] as $brd) :?>
            <tr>
                <td class="text-center"><?=$brd['nums']?></td>
                <td class="text-center"><?=$brd['brd_key']?></td>
                <td class="text-center"><?=$brd['brd_title']?><a class="btn btn-default btn-xs ML10" data-toggle="tooltip"  title="게시판 바로가기" target="_blank" href="<?=base_url('board/'.$brd['brd_key'])?>"><i class="fal fa-external-link-square"></i></a></td>
                <td class="text-center"><?=$brd['brd_skin_l']?></td>
                <td class="text-center"><?=$brd['brd_page_limit']=='Y'?$brd['brd_page_rows']:'미사용'?></td>
                <td class="text-center"><?=number_format($brd['brd_count_post'])?></td>
                <td class="text-center"><?=$brd['brd_use_category']=='Y'?'사용':'미사용'?></td>
                <td class="text-center"><?=$brd['brd_use_reply']=='Y'?'사용':'미사용'?></td>
                <td class="text-center"><?=$brd['brd_use_comment']=='Y'?'사용':'미사용'?></td>
                <td class="text-center"><?=$brd['brd_use_attach']=='Y'?'사용':'미사용'?></td>
                <td class="text-center"><?=$brd['brd_lv_list']?></td>
                <td class="text-center"><?=$brd['brd_lv_read']?></td>
                <td class="text-center"><?=$brd['brd_lv_write']?></td>
                <td class="text-center">
                    <?php if($brd['brd_use_category']=='Y') :?>
                        <a class="btn btn-default btn-xs" href="<?=base_url('admin/board/category/'.$brd['brd_key'])?>" data-toggle="tooltip"  title="카테고리 관리"><i class="far fa-sitemap"></i></a>
                    <?php endif;?>
                    <button class="btn btn-default btn-xs" data-button="copy-board" data-key="<?=$brd['brd_key']?>" data-toggle="tooltip"  title="게시판 복사"><i class="far fa-copy"></i></button>
                    <a class="btn btn-default btn-xs" href="<?=base_url('admin/board/form/'.$brd['brd_key'])?>" data-toggle="tooltip"  title="정보 수정"><i class="far fa-pencil"></i></a>
                    <a class="btn btn-danger btn-xs" href="<?=base_url('admin/board/remove/'.$brd['brd_key'])?>" onclick="return confirm('해당 게시판을 삭제하시겠습니까?');" data-toggle="tooltip"  title="게시판 삭제"><i class="far fa-trash"></i></a>
                </td>
            </tr>
        <?php endforeach;?>

        <?php if(count($board_list['list']) == 0) :?>
            <tr>
                <td colspan="14" class="empty">등록된 게시판이 없습니다.</td>
            </tr>
        <?php endif;?>
        </tbody>
    </table>
</div>

<div class="ax-button-group ax-button-group-bottom">
    <div class="right">
        <a href="<?=base_url('admin/board/form')?>" class="btn btn-default"><i class="far fa-plus-circle"></i> 게시판 추가하기</a>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();

        $('[data-button="copy-board"]').click(function(){
            var brd_key = $(this).data('key');
            APP.MODAL.callback = function() {
                location.reload();
            };
            APP.MODAL.open({
                iframe : {
                    url : '/admin/board/board_copy/'+brd_key,
                    param : {
                        brd_key : brd_key
                    }
                },
                header : {
                    title : '게시판 복사하기'
                },
                width:400,
                height:300
            });
        });
    });
</script>