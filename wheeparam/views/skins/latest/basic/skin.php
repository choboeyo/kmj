<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title"><?=$board['brd_title']?> 최신글</h4>
    </div>
    <table class="table table-condensed">
        <tbody>
        <?php foreach($list as $post) :?>
        <tr>
            <td><a href="<?=$post['link']?>"><?=$post['post_title']?></a></td>
            <td><?=$post['post_datetime']?></td>
        </tr>
        <?php endforeach;?>
        <?php if(count($list) == 0) :?>
        <tr>
            <td colspan="3" class="text-center">등록된 글이 없습니다.</td>
        </tr>
        <?php endif;?>
        </tbody>
    </table>
    <div class="panel-footer text-right">
        <a class="btn btn-default btn-xs" href="<?=base_url("board/{$board['brd_key']}")?>">더 보기</a>
    </div>
</div>