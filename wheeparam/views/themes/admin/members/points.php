<div class="page-header">
    <h2 class="page-title"><?=$this->site->config('point_name')?> 관리</h2>
</div>

<div data-ax5grid>
    <table>
        <thead>
        <tr>
            <th class="W100">#</th>
            <th class="W150">일시</th>
            <th class="W150">사용자</th>
            <th class="W100"><?=$this->site->config('point_name')?></th>
            <th>내용</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($list['list'] as $row) :?>
        <tr>
            <td class="text-right"><?=number_format($row['nums'])?></td>
            <td class="text-center"><?=$row['mpo_regtime']?></td>
            <td class="text-center"><?=$row['mem_nickname']?><?=display_member_menu($row['mem_idx'], '<i class="far fa-cog"></i>', $row['mem_status'])?></td>
            <td class="text-right"><?=$row['mpo_value']>0?'+':''?><?=$row['mpo_value']?></td>
            <td><?=$row['mpo_description']?></td>
        </tr>
        <?php endforeach;?>
        <?php if(count($list['list']) == 0) :?>
        <tr>
            <td class="empty" colspan="5">검색된 내역이 없습니다.</td>
        </tr>
        <?php endif;?>
        </tbody>
    </table>
</div>

<div class="text-center MT10">
    <?=$pagination?>
</div>