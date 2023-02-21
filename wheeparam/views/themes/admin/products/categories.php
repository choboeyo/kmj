<?php $this->site->add_js('https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js')?>
<?php $this->site->add_css('https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css')?>
<div class="page-header">
    <h1 class="page-title">상품 분류 관리</h1>
</div>

<div class="row">

    <div class="col-sm-4">
        <div class="ax-button-group">
            <div class="left">
                <p class="help-block">※ 상품분류에 표시되는 상품개수는 새로고침하여야 최신화 되어 보여집니다.</p>
            </div>
            <div class="right">
                <button type="button" class="btn btn-default" onclick="openForm(0)"><i class="fal fa-plus-circle"></i> 대메뉴 추가</button>
            </div>
        </div>

        <div class="dd" id="category-list">
            <ol class="dd-list">
                <?php foreach($categoryList as $row):?>
                    <li class="dd-item" data-id="<?=$row['cat_id']?>">
                        <i class="fas fa-bars dd-handle"></i>
                        <div class="dd-content">
                            <div class="item-name"><?=$row['cat_title']?> (<?=number_format($row['cat_product_count'])?>) <a href="<?=base_url('products/category/'.$row['cat_id'])?>" target="_blank"><i class="fas fa-external-link"></i></a></div>
                            <div class="item-actions">
                                <button class="btn btn-default MR5" type="button" onclick="openForm('<?=$row['cat_id']?>')"><i class="fa fa-plus"></i> 하위분류추가</button>
                                <button class="btn btn-default MR5" type="button" onclick="openForm('<?=$row['cat_parent_id']?>','<?=$row['cat_id']?>')"><i class="fa fa-pencil"></i></button>
                                <button class="btn btn-danger" type="button" onclick="deleteItem('<?=$row['cat_id']?>')" <?=count($row['children'])===0?'':'disabled'?>><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                        <?php if(count($row['children']) > 0) :?>
                            <ol class="dd-list">
                                <?php foreach($row['children'] as $row2):?>
                                    <li class="dd-item" data-id="<?=$row2['cat_id']?>">
                                        <i class="fas fa-bars dd-handle"></i>
                                        <div class="dd-content">
                                            <div class="item-name"><?=$row2['cat_title']?> (<?=number_format($row2['cat_product_count'])?>) <a href="<?=base_url('products/category/'.$row2['cat_id'])?>" target="_blank"><i class="fas fa-external-link"></i></a></div>
                                            <div class="item-actions">
                                                <button class="btn btn-default MR5" type="button" onclick="openForm('<?=$row2['cat_id']?>')"><i class="fa fa-plus"></i> 하위분류추가</button>
                                                <button class="btn btn-default MR5" type="button" onclick="openForm('<?=$row2['cat_parent_id']?>','<?=$row2['cat_id']?>')"><i class="fa fa-pencil"></i></button>
                                                <button class="btn btn-danger" type="button" onclick="deleteItem('<?=$row2['cat_id']?>')" <?=count($row2['children'])===0?'':'disabled'?>><i class="fa fa-trash"></i></button>
                                            </div>
                                        </div>

                                        <?php if(count($row2['children']) > 0) :?>
                                            <ol class="dd-list">
                                                <?php foreach($row2['children'] as $row3):?>
                                                    <li class="dd-item" data-id="<?=$row3['cat_id']?>">
                                                        <i class="fas fa-bars dd-handle"></i>
                                                        <div class="dd-content">
                                                            <div class="item-name"><?=$row3['cat_title']?> (<?=number_format($row3['cat_product_count'])?>) <a href="<?=base_url('products/category/'.$row3['cat_id'])?>" target="_blank"><i class="fas fa-external-link"></i></a></div>
                                                            <div class="item-actions">
                                                                <button class="btn btn-default MR5" type="button" onclick="openForm('<?=$row3['cat_parent_id']?>','<?=$row3['cat_id']?>')"><i class="fa fa-pencil"></i></button>
                                                                <button class="btn btn-danger" type="button" onclick="deleteItem('<?=$row3['cat_id']?>')"><i class="fa fa-trash"></i></button>
                                                            </div>
                                                        </div>
                                                    </li>
                                                <?php endforeach;?>
                                            </ol>
                                        <?php endif;?>

                                    </li>
                                <?php endforeach;?>
                            </ol>
                        <?php endif;?>
                    </li>
                <?php endforeach;?>
            </ol>
        </div>


    </div>

    <div class="col-sm-8" data-container="formContainer">
    </div>

</div>

<script>
    $(function() {

        $('#category-list').nestable({
            maxDepth: 3,
            callback: function(l, e) {
                var data = $('#category-list').nestable('toArray')
                $.ajax({
                    url: base_url + '/admin/ajax/products/category_sort',
                    type: 'POST',
                    data: {
                        list : data
                    },
                    success: function() {
                        toastr.success('순서 변경이 적용되었습니다.');
                    }
                })
            }
        })

        $(document).on('submit', '[data-form="product-category"]', function(e) {
            e.preventDefault();

            var data = $(this).serialize();
            var cat_parent_id = $(this).find('[name="cat_parent_id"]').val()
            var cat_id = $(this).find('[name="cat_id"]').val()

            $.ajax({
                url: base_url + '/admin/ajax/products/category',
                type: 'POST',
                data: data,
                success: function() {
                    location.reload();
                }
            })
        });
    })

    /**
     * 상품 분류 추가/수정 폼을 연다
     * @param parentId
     * @param id
     */
    function openForm(parentId, id)
    {
        id = typeof id !== 'undefined' && id ? id : ''
        $.ajax({
            url: base_url + '/admin/products/categories_form/' + parentId + ( id ? '/' + id : '' ),
            type:'GET',
            success: function(res) {
                $('[data-container="formContainer"]').html(res);
            }
        })
    }

    /**
     * 상품 분류 삭제
     * @param id
     */
    function deleteItem(id) {
        if(! confirm('선택하신 상품분류를 삭제하시겠습니까?')) return;

        $.ajax({
            url: base_url + '/admin/ajax/products/category',
            type: "DELETE",
            data: {
                cat_id: id
            },
            success:function() {
                location.reload();
            }
        })
    }
</script>