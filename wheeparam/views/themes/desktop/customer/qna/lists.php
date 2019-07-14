<form class="form-inline" autocomplete="off" method="get" accept-charset="UTF-8">
    <select class="form-control">
        <option value="title">질문제목</option>
        <option value="name">질문자</option>
    </select>
    <input class="form-control" required>
    <button class="btn btn-default"><i class="fal fa-search"></i> 검색하기</button>
</form>
<table class="table">
    <thead>
    <tr>
        <th>순번</th>
        <th>질문유형</th>
        <th>질문제목</th>
        <th>질문자</th>
        <th>답변여부</th>
        <th>작성일시</th>
    </tr>
    </thead>
    <tbody>
    <?php if(count($lists) == 0) :?>
    <tr>
        <td colspan="5" class="text-center empty">등록된 질문이 없습니다.</td>
    </tr>
    <?php endif;?>
    <?php foreach($lists as $row):?>
    <tr>
        <td class="text-right"><?=$row['nums']?></td>
        <td class="text-right"><?=$row['qnc_title']?></td>
        <td class="text-left">
            <a href="<?=base_url('customer/qna/'.$row['qna_idx'])?>"><i class="fal fa-lock"></i> <?=$row['qna_title']?></a>
        </td>
        <td class="text-center"><?=$row['qna_name']?></td>
        <td class="text-center"><?=$row['is_answered']?'답변완료':'미답변'?></td>
        <td class="text-center"><?=$row['reg_datetime']?></td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>
<div class="text-center"><?=$pagination?></div>
<a href="<?=base_url('customer/qna/write')?>"><i class="fal fa-pencil"></i> 질문작성하기</a>