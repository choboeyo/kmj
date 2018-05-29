<div class="page-header">
    <h1 class="page-title">팝업 관리</h1>
</div>

<div class="H10"></div>
<div class="ax-button-group">
    <div class="left">
        <h4>팝업 관리</h4>
    </div>
    <div class="right">
        <a class="btn btn-default" href="<?=base_url('admin/management/popup_form')?>"><i class="far fa-plus-circle"></i> 신규 팝업 등록</a>
    </div>
</div>

<div data-ax5grid>
    <table>
        <thead>
        <tr>
            <th>번호</th>
            <th>팝업 종류</th>
            <th>팝업 이름</th>
            <th>팝업 너비</th>
            <th>팝업 높이</th>
            <th>표시 시작</th>
            <th>표시 종류</th>
            <th>상태</th>
            <th>관리</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($popup_list['list'] as $row) :?>
        <tr>
            <td class="text-center W100"><?=$row['nums']?></td>
            <td class="text-center W100"><?=$row['pop_type']=='N'?'팝업레이어':'팝업창'?></td>
            <td class="text-left"><?=$row['pop_title']?></td>
            <td class="text-center W100"><?=$row['pop_width']?></td>
            <td class="text-center W100"><?=$row['pop_height']?></td>
            <td class="text-center W200"><?=$row['pop_start']?></td>
            <td class="text-center W200"><?=$row['pop_end']?></td>
            <td class="text-center W100">
                <?php if( strtotime($row['pop_start']) <= time() && strtotime($row['pop_end'])  >= time()) :?>
                <label class="label label-success">표시중</label>
                <?php else :?>
                <label class="label label-default">미 표시중</label>
                <?php endif;?>
            </td>
            <td class="text-center W150">
                <a class="btn btn-default btn-xs" href="<?=base_url('admin/management/popup_form/'.$row['pop_idx'])?>"><i class="far fa-pencil"></i> 수정</a>
                <a class="btn btn-danger btn-xs" onclick="return confirm('해당 팝업을 삭제하시겠습니까?');" href="<?=base_url('admin/management/popup_delete/'.$row['pop_idx'])?>"><i class="far fa-trash"></i> 삭제</a>
            </td>
        </tr>
        <?php endforeach;?>
        <?php if(count($popup_list['list']) <=0) :?>
        <tr>
            <td colspan="9" class="empty">등록된 팝업이 없습니다.</td>
        </tr>
        <?php endif;?>
        </tbody>
    </table>
</div> 