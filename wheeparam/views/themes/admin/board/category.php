<div class="page-header">
    <h1 class="page-title">'<?=$board['brd_title']?>' 게시판 카테고리 설정</h1>
</div>

<div class="row">
    <div class="col-sm-3">
        <ul class="nav nav-cards">
            <li role="presentation"><a class="card" href="<?=base_url('admin/board/form/'.$board['brd_key'])?>">게시판 정보 수정</a></li>
            <li role="presentation" class="active"><a href="#" class="card selected">카테고리 설정</a></li>
        </ul>
    </div>
    <div class="col-sm-9">

        <ul class="category-list">
            <li>
                <div>
                    <?=$board['brd_title']?>
                    <button type="button" class="btn btn-xs btn-default" onclick="APP.BOARD.CATEGORY.form('<?=$board['brd_key']?>',0)"><i class="fal fa-plus-circle"></i></button>
                </div>
                <ul id="category-list">
                    <?php foreach($board['category'] as $cate) : ?>
                        <li data-idx="<?=$cate['bca_idx']?>">
                            <div>
                                <i class="fal fa-folder-open"></i> <span class="move-item"><?=$cate['bca_name']?></span>
                                <button type="button" class="btn btn-xs btn-default" onclick="APP.BOARD.CATEGORY.form('<?=$board['brd_key']?>', <?=$cate['bca_idx']?>)"><i class="fal fa-plus-circle"></i></button>
                                <button type="button" class="btn btn-xs btn-default" onclick="APP.BOARD.CATEGORY.form('<?=$board['brd_key']?>', 0,<?=$cate['bca_idx']?>)"><i class="fal fa-pencil"></i></button>
                                <button type="button" class="btn btn-xs btn-danger" onclick="APP.BOARD.CATEGORY.remove('<?=$cate['bca_idx']?>')"><i class="fal fa-trash"></i></button>
                            </div>
                            <ul class="items">

                                <?php foreach($cate['items'] as $ct):?>
                                    <li data-idx="<?=$ct['bca_idx']?>">
                                        <div>
                                            <span class="move-item"><?=$ct['bca_name']?></span>
                                            <button type="button" class="btn btn-xs btn-default" onclick="APP.BOARD.CATEGORY.form('<?=$board['brd_key']?>', <?=$cate['bca_idx']?>, <?=$ct['bca_idx']?>)"><i class="fal fa-pencil"></i></button>
                                            <button type="button" class="btn btn-xs btn-danger" onclick="APP.BOARD.CATEGORY.remove('<?=$ct['bca_idx']?>')"><i class="fal fa-trash"></i></button>
                                        </div>
                                    </li>
                                <?php endforeach;?>
                            </ul>
                        </li>
                    <?php endforeach;?>
                </ul>
            </li>
        </ul>
    </div>
</div>


<script>
$(function(){
    $("#category-list").sortable({
        update: function(){
            var obj = [];
            $("#category-list > li").each(function(){
                obj.push( $(this).data('idx') );
            });
            $.post(base_url+'/ajax/board/category_sort',{brd_key:'<?=$board['brd_key']?>', idxs:obj});
        }
    });

    $("#category-list >li > .items").sortable({
        update : function(event, ui) {
            var obj = [];
            $("#category-list >li > .items > li").each(function(){
                obj.push( $(this).data('idx') );
            });
            $.post(base_url+'/ajax/board/category_sort',{brd_key:'<?=$board['brd_key']?>', idxs:obj});
        }
    });
});
</script>