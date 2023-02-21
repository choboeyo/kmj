<div class="row">
    <div class="col-sm-4">

        <div class="ax-button-group">
            <div class="left">
                <h4>진열장 목록</h4>
            </div>
            <div class="right">
                <button type="button" class="btn btn-default" data-button="display-form" data-idx=""><i class="fal fa-plus-circle"></i> 진열장 추가</button>
            </div>
        </div>

        <div class="grid">
            <table>
                <thead>
                <tr>
                    <th>진열장이름</th>
                    <th class="W150">관리</th>
                </tr>
                </thead>
                <tbody>
                <?php if(count($list) == 0):?>
                <tr>
                    <td colspan="2" class="empty">등록된 진열장이 없습니다.</td>
                </tr>
                <?php endif;?>
                <?php foreach($list as $row):?>
                <tr class="<?=$row['dsp_idx']==$dsp_idx?'active':''?>">
                    <td><a href="<?=base_url('products/display/'.$row['dsp_key'])?>" target="_blank"><?=$row['dsp_title']?></a></td>
                    <td>
                        <a class="btn btn-default btn-xs MR5" href="<?=base_url('admin/products/displays/'.$row['dsp_idx'])?>">품목관리</a>
                        <button type="button" class="btn btn-default btn-xs MR5" data-button="display-form" data-idx="<?=$row['dsp_idx']?>">수정</button>
                        <button class="btn btn-danger btn-xs" type="button" data-button="btn-delete-display" data-idx="<?=$row['dsp_idx']?>">삭제</button>
                    </td>
                </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-sm-8">
        <?php if($dsp_idx):?>

            <?php echo form_open('products/display_sort')?>
            <div class="ax-button-group">
                <div class="left">
                    <h4>진열장 [<?=$display_info['dsp_title']?>] 품목 관리</h4>
                </div>
                <div class="right">
                    <button type="button" class="btn btn-default" data-button="display-item-add"><i class="fal fa-plus-circle"></i> 품목 추가</button>
                </div>
            </div>

            <div class="grid">
                <table style="table-layout:fixed">
                    <thead>
                    <tr>
                        <th class="W30"></th>
                        <th>상품명</th>
                        <th class="W80">관리</th>
                    </tr>
                    </thead>
                    <tbody data-toggle="sortable" data-key="dspi_idx" data-sort="dspi_sort" data-table="products_display_items">
                    <?php if(count($items_list) === 0) :?>
                    <tr>
                        <td colspan="3" class="empty">진열된 품목이 없습니다.</td>
                    </tr>
                    <?php endif;?>
                    <?php foreach($items_list as $row):?>
                    <tr>
                        <td class="text-center">
                            <span class="move-grip"></span>
                            <input type="hidden" name="dspi_idx[]" value="<?=$row['dspi_idx']?>">
                        </td>
                        <td class="W60">
                            <div class="prd-wrap" style="display:flex;">
                                <figure style="flex-shrink: 0;margin-bottom:0;">
                                    <?php if($row['thumbnail'] && file_exists(FCPATH . $row['thumbnail'])) :?>
                                        <img src="<?=thumbnail($row['thumbnail'], 60)?>" width="60">
                                    <?php else :?>
                                        <img src="http://placehold.it/80x80?text=NO+IMAGE" width="60">
                                    <?php endif;?>
                                </figure>

                                <div class="text-info" style="margin-left:.5rem; flex:1">
                                    <div><?=$row['category_name']?></div>
                                    <a href="<?=base_url('products/items/'.$row['prd_idx'])?>" target="_blank"><?=$row['prd_name']?></a>
                                </div>
                            </div>
                        </td>
                        <td>
                            <button type="button" class="btn btn-xs btn-danger" data-button="remove-display-item" data-idx="<?=$row['prd_idx']?>">품목 제외</button>
                        </td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>

                </table>
            </div>
            <?php echo form_close()?>

        <?php endif;?>
    </div>
</div>

<script>
    $(function() {
        $('[data-button="display-form"]').click(function(){
            var dsp_idx = $(this).data('idx');
            dsp_idx = typeof dsp_idx !== 'undefined' && dsp_idx ? dsp_idx : ''

            APP.MODAL.callback = function() {
                location.reload();
            }
            APP.MODAL.open({
                iframe : {
                    url : base_url + '/admin/products/displays_form/' + dsp_idx
                },
                width: 500,
                height: 400,
                header : {
                    title : '진열장 정보 입력'
                }
            });
        });

        <?php if($dsp_idx):?>
        $('[data-button="display-item-add"]').click(function(){
            var dsp_idx = '<?=$dsp_idx?>';
            APP.MODAL.callback = function() {
                location.reload();
            }
            APP.MODAL.open({
                iframe : {
                    url : base_url + '/admin/products/displays_item_add/' + dsp_idx
                },
                width: 800,
                height: 600,
                header : {
                    title : '진열장 품목 추가'
                }
            });
        });

        $('[data-button="remove-display-item"]').click(function() {
            var dsp_idx = '<?=$dsp_idx?>';
            var prd_idx = $(this).data('idx');

            if(! confirm('해당 상품을 현재 진열장에서 제외시키겠습니까?')) return;

            $.ajax({
                url: base_url + '/admin/ajax/products/display_items',
                type: 'DELETE',
                data: {
                    dsp_idx: dsp_idx,
                    prd_idx: prd_idx
                },
                success: function() {
                    location.reload();
                }
            })
        })
        <?php endif;?>
    })
</script>