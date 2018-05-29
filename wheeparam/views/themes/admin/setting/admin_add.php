<div class="page-header">
    <h2 class="page-title">관리자 추가</h2>
</div>

<form class="form-flex" autocomplete="off">
    <div class="form-group">
        <label class="control-label control-label-sm">회원 검색</label>
        <div class="controls">
            <select class="form-control form-control-inline" name="scol">
                <option value="mem_nickname" <?=$scol=='mem_nickname'?'selected':''?>>닉네임 검색</option>
                <option value="mem_userid" <?=$scol=='mem_userid'?'selected':''?>>아이디 검색</option>
            </select>
            <input class="form-control form-control-inline" name="stxt" value="<?=$stxt?>">
            <button class="btn btn-lg btn-default">검색</button>
        </div>
    </div>
</form>

<div data-ax5grid>
    <table>
        <thead>
        <tr>
            <th>아이디</th>
            <th>닉네임</th>
            <th>권한레벨</th>
            <th>관리</th>
        </tr>
        </thead>
        <tbody>
        <?php if(count($lists) == 0) :?>
        <tr>
            <td colspan="4" class="empty">검색된 회원이 없습니다.</td>
        </tr>
        <?php endif;?>
        <?php foreach($lists as $row):?>
        <tr>
            <td class="text-center"><?=$row['mem_userid']?></td>
            <td class="text-center"><?=$row['mem_nickname']?></td>
            <td class="text-center"><?=$row['mem_auth']?></td>
            <td class="text-center">
                <button type="button" class="btn btn-primary" data-button="admin-add-ok" data-idx="<?=$row['mem_idx']?>">추가하기</button>
            </td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<script>
$(function(){
    $('[data-button="admin-add-ok"]').click(function(e){
        e.preventDefault();

        var idx = $(this).data('idx');
        if(! confirm('해당 회원을 관리자로 추가하시겠습니까?')) return false;

        $.ajax({
            url : '/ajax/members/admin',
            type : 'POST',
            async: false,
            cache: false,
            data : {
                mem_idx : idx
            },
            success:function() {
                alert('지정한 사용자를 관리자로 추가하였습니다.');
                location.reload();
            }
        })
    });
});
</script>