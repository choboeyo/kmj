<div class="page-header">
    <h1 class="page-title">관리자 관리</h1>
</div>


<div class="row">
    <div class="col-lg-6 col-md-8 col-sm-10">
        <div class="ax-button-group">
            <div class="left">
                <h4>등록된 관리자 목록</h4>
            </div>
            <div class="right">
                <button type="button" class="btn btn-default" data-button="admin-add">관리자 추가</button>
            </div>
        </div>

        <div data-ax5grid>
            <table>
                <thead>
                <tr>
                    <th>#</th>
                    <th>아이디</th>
                    <th>이름</th>
                    <th>권한레벨</th>
                    <th>관리</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($lists as $i=>$row) :?>
                    <tr>
                        <td class="text-center"><?=$i+1?></td>
                        <td class="text-center"><?=$row['mem_userid']?></td>
                        <td class="text-center"><?=$row['mem_nickname']?></td>
                        <td class="text-center"><?=$row['mem_auth']?></td>
                        <td class="text-center">
                            <?php if($row['mem_idx'] != 1):?>
                            <button type="button" class="btn btn-danger" data-button="admin-remove" data-idx="<?=$row['mem_idx']?>">삭제</button>
                            <?php endif;?>
                        </td>                        
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('[data-button="admin-add"]').click(function(e){
            APP.MODAL.callback = function() {
                location.reload();
            };
            APP.MODAL.open({
                iframe : {
                    url : '/admin/setting/admin_add'
                },
                header : {
                    title : '관리자 추가'
                },
                width: 800,
                height:600
            })
        });

        $('[data-button="admin-remove"]').click(function(e){
            e.preventDefault();
            var idx = $(this).data('idx');

            if(! confirm('해당 사용자의 관리자 권한을 제거하고, 권한레벨을 초기값으로 설정하시겠습니까?')) return;

            $.ajax({
                url : '/ajax/members/admin',
                type : 'DELETE',
                data : {
                    mem_idx : idx
                },
                success:function(res) {
                    alert('지정한 회원의 관리자 권한을 삭제하였습니다.');
                    location.reload();
                }
            })
        });
    });
</script>