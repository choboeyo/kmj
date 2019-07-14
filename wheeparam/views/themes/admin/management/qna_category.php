<div class="ax-button-group">
    <div class="left">
        <h4>Q&amp;A 분류 관리</h4>
    </div>
    <div class="right">
        <button type="button" class="btn btn-default" data-button="form-qna-category" data-idx=""><i class="fal fa-plus-circle"></i> 신규 등록</button>
    </div>
</div>

<div class="grid">
    <table>
        <thead>
        <tr>
            <th class="W20"></th>
            <th>분류 이름</th>
            <th class="W80">관리</th>
        </tr>
        </thead>
        <tbody data-toggle="sortable" data-key="qnc_idx" data-sort="sort" data-table="qna_category">
        <?php foreach($lists as $row):?>
        <tr>
            <td class="text-center">
                <span class="move-grip"></span>
                <input type="hidden" name="qnc_idx[]" value="<?=$row['qnc_idx']?>">
            </td>
            <td><?=$row['qnc_title']?></td>
            <td class="text-center">
                <button type="button" class="btn btn-default btn-xs MR5" data-button="form-qna-category" data-idx="<?=$row['qnc_idx']?>"><i class="fal fa-pencil"></i></button>
                <button type="button" class="btn btn-danger btn-xs" data-button="delete-qna-category" data-idx="<?=$row['qnc_idx']?>"><i class="fal fa-trash"></i></button>
            </td>
        </tr>
        <?php endforeach;?>
        <?php if(count($lists) == 0) :?>
        <tr>
            <td colspan="3" class="empty">등록된 분류가 없습니다.</td>
        </tr>
        <?php endif;?>
        </tbody>
    </table>
</div>

<script>
    $(function() {
        $('[data-button="form-qna-category"]').click(function (e) {
            var idx = $(this).data('idx');
            idx = typeof idx != 'undefined' && idx ? idx :'';

            APP.MODAL2.callback = function() {
                location.reload();
            };

            APP.MODAL2.open({
                iframe: {
                    url: base_url + '/admin/management/qna_category_form/' + idx,
                },
                width: 340,
                height: 200
            });
        });

        $('[data-button="delete-qna-category"]').click(function(e) {
            var idx = $(this).data('idx');
            idx = typeof idx != 'undefined' && idx ? idx : false;
            if(! idx) return;
            if(! confirm('해당 분류를 삭제하시겠습니까?')) return;
            $.ajax({
                url: base_url + '/admin/ajax/management/qna-category',
                type:'DELETE',
                data: {
                    qnc_idx:idx
                },
                success:function() {
                    location.reload();
                }
            })

        });
    })
</script>