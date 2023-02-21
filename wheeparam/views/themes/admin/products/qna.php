<div class="ax-button-group">
    <div class="left">
        <h4>상품문의</h4>
    </div>
</div>
<div class="grid">
    <table>
        <thead>
        <tr>
            <th class="W100">상태</th>
            <th>상품</th>
            <th class="W120">질문자</th>
            <th class="W160">질문일시</th>
            <th class="W80">비밀글</th>
            <th class="W120">답변자</th>
            <th class="W160">답변일시</th>
            <th class="W120">관리</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($list as $row):?>
        <tr>
            <td class="text-center">
                <?php if($row['qa_is_answer'] == 'Y'):?>
                <label class="label label-success">답변완료</label>
                <?php else:?>
                <label class="label label-danger">미답변</label>
                <?php endif;?>
            </td>
            <td><a href="<?=base_url('products/items/'.$row['prd_idx'])?>"><?=$row['prd_name']?></a></td>
            <td class="text-center"><?=$row['nickname']?></td>
            <td class="text-center"><?=$row['reg_datetime']?></td>
            <td class="text-center"><?=$row['qa_secret']==='Y'?'예':''?></td>
            <td class="text-center"><?=$row['a_nickname']?></td>
            <td class="text-center"><?=$row['qa_a_datetime']!='0000-00-00 00:00:00'?$row['qa_a_datetime']:''?></td>
            <td class="text-center">
                <a class="btn btn-default" href="<?=base_url('admin/products/qna_form/'.$row['qa_idx'])?>">답변달기</a>
            </td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<div class="text-center MT10"><?=$pagination?></div>