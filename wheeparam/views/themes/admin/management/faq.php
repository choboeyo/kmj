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
                <button type="button" class="btn btn-default" onclick="faq.category.form();"><i class="fal fa-plus-circle"></i> 분류 추가</button>
            </div>
        </div>

        <div class="grid">
            <table>
                <thead>
                <tr>
                    <th class="W20"></th>
                    <th>분류이름</th>
                    <th class="W50">등록</th>
                    <th class="W80">관리</th>
                </tr>
                </thead>
                <tbody data-toggle="sortable" data-key="fac_idx" data-sort="sort" data-table="faq_category">
                <?php foreach($faq_category['list'] as $row) :?>
                    <tr class="<?=isset($fac_idx)&&$fac_idx==$row['fac_idx']?'active':''?>">
                        <td class="text-center"><span class="move-grip"></span><input type="hidden" name="fac_idx[]" value="<?=$row['fac_idx']?>"></td>
                        <td class=""><i class="fal <?=isset($fac_idx)&&$fac_idx==$row['fac_idx']?'fa-folder-open':'fa-folder'?>"></i>&nbsp;<a href="<?=base_url('admin/management/faq/'.$row['fac_idx'])?>"><?=$row['fac_title']?></a></td>
                        <td class="text-right W50"><?=number_format($row['fac_count'])?></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-default btn-sm MR5" onclick="faq.category.form('<?=$row['fac_idx']?>');"><i class="fal fa-pencil"></i></button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="faq.category.remove('<?=$row['fac_idx']?>');"><i class="fal fa-trash"></i></button>
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
                    <button type="button" class="btn btn-default" onclick="faq.form('<?=$fac_idx?>');"><i class="fal fa-plus-circle"></i> FAQ 추가</button>
                </div>
            </div>

            <div class="grid">
                <table>
                    <thead>
                    <tr>
                        <th class="W20"></th>
                        <th>FAQ 제목</th>
                        <th class="W80">수정자</th>
                        <th class="W150">수정일시</th>
                        <th class="W80">관리</th>
                    </tr>
                    </thead>
                    <tbody data-toggle="sortable" data-key="faq_idx" data-sort="sort" data-table="faq">
                    <?php foreach($faq_list['list'] as $row) :?>
                        <tr>
                            <td class="text-center"><span class="move-grip"></span><input type="hidden" name="faq_idx[]" value="<?=$row['faq_idx']?>"></td>
                            <td><?=$row['faq_title']?></td>
                            <td class="text-center"><?=$row['upd_username']?></td>
                            <td><?=$row['upd_datetime']?></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-default btn-xs MR5" onclick="faq.form('<?=$row['fac_idx']?>','<?=$row['faq_idx']?>');"><i class="fal fa-pencil"></i></button>
                                <button type="button" class="btn btn-danger btn-xs" onclick="faq.remove('<?=$row['faq_idx']?>');"><i class="fal fa-trash"></i></button>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    <?php if(count($faq_list['list']) == 0) :?>
                        <tr>
                            <td colspan="5" class="empty">등록된 FAQ가 없습니다.</td>
                        </tr>
                    <?php endif;?>
                    </tbody>
                </table>
            </div>
        <?php endif;?>
    </div>

</div>

<script>
    var faq = {};
    faq.form = function(fac_idx, faq_idx) {
        var faq_idx = (typeof faq_idx == 'string' || typeof faq_idx == 'number' ) ? faq_idx : null;
        var fac_idx = (typeof fac_idx == 'string' || typeof fac_idx == 'number' ) ? fac_idx : null;
        if(! fac_idx) {
            alert('FAQ 분류 정보가 없습니다.');
            return false;
        }

        APP.MODAL.open({
            width: 800,
            height :650,
            header : {
                title : faq_idx ? 'FAQ 정보 수정' : 'FAQ 추가'
            },
            callback : function(){
                location.reload();
            },
            iframe : {
                method : 'get',
                url : base_url + '/admin/management/faq_form',
                param : {
                    fac_idx : fac_idx,
                    faq_idx : faq_idx
                }
            }
        });
    };

    faq.remove = function(faq_idx) {
        if(typeof faq_idx == 'undefined' || ! faq_idx || faq_idx.trim() == '') {
            alert('잘못된 접근입니다.');
        }

        if(! confirm('해당 FAQ를 삭제하시겠습니까?')) return false;

        $.ajax({
            url : base_url + '/admin/ajax/management/faq',
            type : 'DELETE',
            async:false,
            cache:false,
            data:{faq_idx:faq_idx},
            success:function(res){
                alert('FAQ가 삭제되었습니다.');
                location.reload();
            }
        });
    };

    /**
     * FAQ 분류
     * @type {{}}
     */
    faq.category = {};
    faq.category.form = function(fac_idx)
    {
        var fac_idx = (typeof fac_idx == 'string' || typeof fac_idx == 'number' ) ? fac_idx : null;
        APP.MODAL.open({
            width: $(window).width() > 600 ? 600 : $(window).width(),
            height :250,
            header : {
                title : fac_idx ? 'FAQ 분류 정보 수정' : 'FAQ 분류 추가'
            },
            callback : function(){
                location.reload();
            },
            iframe : {
                method : 'get',
                url : base_url + '/admin/management/faq_category_form',
                param : {
                    fac_idx : fac_idx
                }
            }
        });
    };

    faq.category.remove = function(fac_idx) {
        if(typeof fac_idx == 'undefined' || ! fac_idx || fac_idx.trim() == '') {
            alert('잘못된 접근입니다.');
        }
        var count = 0;
        $.ajax({
            url : base_url + '/admin/ajax/management/faq',
            type : 'get',
            async:false,
            cache: false,
            data : {fac_idx:fac_idx},
            success:function(res){
                count = res.total_count;
            }
        });

        var msg = ( count > 0 ) ? '해당 FAQ 분류에 ' + count + '개의 FAQ 목록이 등록되어 있습니다.\nFAQ 분류을 삭제할시 등록된 FAQ 목록도 같이 삭제됩니다.\n\n계속 하시겠습니까?' : 'FAQ 분류을 삭제하시겠습니까?';
        if(! confirm(msg)) return false;

        $.ajax({
            url : base_url+ '/admin/ajax/management/faq_category',
            type : 'delete',
            async:false,
            cache:false,
            data:{fac_idx:fac_idx},
            success:function(res){
                alert('FAQ 분류가 삭제되었습니다.');
                location.href= base_url + "/admin/management/faq";
            }
        });
    };
</script>