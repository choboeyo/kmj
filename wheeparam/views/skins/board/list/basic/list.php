<div class="container">
    <div class="page-header">
        <h1 class="page-title"><?=$board['brd_title']?></h1>

        <!-- START :: 게시판 RSS-->
        <div class="pull-right">
            <a href="<?=$board['link']['rss']?>" target="_blank"><i class="fa fa-rss"></i> <?=$board['link']['rss']?></a>
        </div>
        <!-- END :: 게시판 RSS-->
    </div>

    <?php if($category_list) :?>
    <ul class="board-category">
        <li><a href="<?=$board['link']['base_url']?>">전체 보기</a></li>
        <?php foreach($category_list as $cate) :?>
        <li><a href="<?=base_url("board/{$board['brd_key']}/?category={$cate}")?>"><?=$cate?></a></li>
        <?php endforeach;?>
    </ul>
    <div class="H30"></div>
    <?php endif;?>

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
                <?php if($post['post_notice']) : ?>
                <label class="label label-danger">공지</label>
                <?php elseif(strlen($post['post_reply']) > 0) : ?>

                <?php else :
                    echo $post['nums'];
                endif;?>
            </td>
            <?php if($use_category) :?>
            <td class="text-center"><?=$post['post_category']?></td>
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
            <td class="text-center"><?=$post['post_nickname']?></td>
            <td class="text-center"><?=$post['post_hit']?></td>
            <td class="text-center"><?=$post['post_datetime']?></td>
        </tr>
        <?php endforeach;?>
        <!-- END :: 글 목록-->

        <!-- START:: 등록된 글이 없는 경우-->
        <?php if(count($list['list'])==0):?>
        <tr>
            <td colspan="6" class="text-center">등록된 글이 없습니다.</td>
        </tr>
        <?php endif;?>
        <!-- END:: 등록된 글이 없는 경우-->
        </tbody>
    </table>

    <div class="clearfix">
        <div class="pull-left">
            <?=form_open(NULL, array("method"=>"get","class"=>"form-inline"))?>
            <select name="scol" class="form-control">
                <option value="title" <?=$scol=='title'?'selected':''?>>제목</option>
                <option value="nickname" <?=$scol=='nickname'?'selected':''?>>닉네임</option>
            </select>
            <input class="form-control" name="stxt" value="<?=$stxt?>">
            <button type="submit" class="btn btn-default">검색</button>
            <?=form_close()?>
        </div>
        <div class="pull-right">
            <?php if($board['auth']['write']) :?>
            <a class="btn btn-primary" href="<?=$board['link']['write']?>">글쓰기</a>
            <?php endif;?>
        </div>
    </div>

    <div class="text-center">
        <?=$pagination?>
    </div>
</div>