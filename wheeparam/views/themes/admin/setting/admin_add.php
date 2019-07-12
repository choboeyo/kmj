<div class="page-header">
    <h2 class="page-title">관리자 추가</h2>
</div>

<form class="form-flex" autocomplete="off">
    <div data-ax-tbl>
        <div data-ax-tr>
            <div data-ax-td>
                <div data-ax-td-label>회원검색</div>
                <div data-ax-td-wrap>
                    <select class="form-control form-control-inline" name="scol">
                        <option value="mem_nickname" <?=$scol=='mem_nickname'?'selected':''?>>닉네임 검색</option>
                        <option value="mem_userid" <?=$scol=='mem_userid'?'selected':''?>>아이디 검색</option>
                    </select>
                </div>
            </div>
            <div data-ax-td>
                <div data-ax-td-wrap>
                    <input class="form-control form-control-inline" name="stxt" placeholder="검색어를 입력하세요" value="<?=$stxt?>">
                </div>
            </div>
            <div data-ax-td>
                <div data-ax-td-wrap>
                    <button class="btn btn-sm btn-default">검색</button>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="H10"></div>
<div class="grid">
    <table>
        <thead>
        <tr>
            <th>아이디</th>
            <th class="W100">닉네임</th>
            <th class="W140">가입일시</th>
            <th class="W140">최근로그인</th>
            <th class="W80">관리</th>
        </tr>
        </thead>
        <tbody>
        <?php if(count($lists) == 0) :?>
        <tr>
            <td colspan="5" class="empty">검색된 회원이 없습니다.</td>
        </tr>
        <?php endif;?>
        <?php foreach($lists as $row):?>
        <tr>
            <td class="text-center"><?=$row['mem_userid']?></td>
            <td class="text-center"><?=$row['mem_nickname']?></td>
            <td class="text-center"><?=$row['mem_regtime']?></td>
            <td class="text-center"><?=$row['mem_logtime']?></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-primary" data-button="admin-add-ok" data-idx="<?=$row['mem_idx']?>">추가하기</button>
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
            url : base_url + '/admin/ajax/setting/admins',
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