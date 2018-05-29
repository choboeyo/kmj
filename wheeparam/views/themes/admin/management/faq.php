<div class="page-header">
    <h1 class="page-title">FAQ 관리</h1>
</div>

<div class="row">

    <div class="col-sm-5">

        <div class="ax-button-group">
            <div class="left">
                <h4>FAQ 분류</h4>
            </div>
            <div class="right">
                <button type="button" class="btn btn-default" onclick="faq.category.form();"><i class="far fa-plus-circle"></i> 분류 추가</button>
            </div>
        </div>

        <div data-ax5grid>
            <table>
                <thead>
                <tr>
                    <th class="W50">순서</th>
                    <th>분류이름</th>
                    <th class="W50">등록</th>
                    <th class="W175">관리</th>
                </tr>
                </thead>
                <tbody id="faq-category-list">
                <?php foreach($faq_category['list'] as $row) :?>
                    <tr>
                        <td class="text-center W50"><i class="far fa-bars sort-handle-icon"></i><input type="hidden" name="fac_idx[]" value="<?=$row['fac_idx']?>"></td>
                        <td class="<?=isset($fac_idx)&&$fac_idx==$row['fac_idx']?'active':''?>"><?=$row['fac_title']?></td>
                        <td class="text-right W50"><?=number_format($row['fac_count'])?></td>
                        <td class="text-center W175">
                            <a href="<?=base_url('admin/management/faq/'.$row['fac_idx'])?>" class="btn btn-default btn-sm"><i class="far <?=isset($fac_idx)&&$fac_idx==$row['fac_idx']?'fa-folder-open':'fa-folder'?>"></i> 관리</a>
                            <button type="button" class="btn btn-default btn-sm" onclick="faq.category.form('<?=$row['fac_idx']?>');"><i class="far fa-pencil"></i> 수정</button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="faq.category.remove('<?=$row['fac_idx']?>');"><i class="far fa-trash"></i> 삭제</button>
                        </td>
                    </tr>
                <?php endforeach;?>
                <?php if(count($faq_category['list']) == 0) :?>
                    <tr>
                        <td colspan="4" class="empty">등록된 FAQ 분류가 없습니다.</td>
                    </tr>
                <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-sm-7">
        <?php if($fac_idx) :?>
            <div class="ax-button-group">
                <div class="left">
                    <h4>[<?=$faq_group['fac_title']?>] 내용 관리</h4>
                </div>
                <div class="right">
                    <button type="button" class="btn btn-default" onclick="faq.form('<?=$fac_idx?>');"><i class="far fa-plus-circle"></i> FAQ 추가</button>
                </div>
            </div>

            <div data-ax5grid>
                <table>
                    <thead>
                    <tr>
                        <th class="W50">순서</th>
                        <th>FAQ 제목</th>
                        <th class="W150">관리</th>
                    </tr>
                    </thead>
                    <tbody id="faq-list">
                    <?php foreach($faq_list['list'] as $row) :?>
                        <tr>
                            <td class="text-center W50"><i class="far fa-bars sort-handle-icon"></i><input type="hidden" name="faq_idx[]" value="<?=$row['faq_idx']?>"></td>
                            <td><?=$row['faq_title']?></td>
                            <td class="text-center W150">
                                <button type="button" class="btn btn-default btn-sm" onclick="faq.form('<?=$row['fac_idx']?>','<?=$row['faq_idx']?>');"><i class="far fa-pencil"></i> 수정</button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="faq.remove('<?=$row['faq_idx']?>');"><i class="far fa-trash"></i> 삭제</button>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    <?php if(count($faq_list['list']) == 0) :?>
                        <tr>
                            <td colspan="3" class="empty">등록된 FAQ가 없습니다.</td>
                        </tr>
                    <?php endif;?>
                    </tbody>
                </table>
            </div>
        <?php endif;?>
    </div>

</div>

<script>
$("#faq-category-list").sortable({
    handle : 'i.far.fa-bars',
    update: function(){
        var sort_array = [];
        $("#faq-category-list input[name='fac_idx[]']").each(function(){
            sort_array.push( $(this).val() );
        });
        $.post('/ajax/faq/category_sort',{sort_idx:sort_array});
    }
});

$("#faq-list").sortable({
    handle : 'i.far.fa-bars',
    update: function(){
        var sort_array = [];
        $("#faq-list input[name='faq_idx[]']").each(function(){
            sort_array.push( $(this).val() );
        });
        $.post('/ajax/faq/sort',{sort_idx:sort_array});
    }
});
</script>