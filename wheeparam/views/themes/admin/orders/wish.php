<div class="ax-button-group">
    <div class="left">
        <h4>상품별 찜순위</h4>
    </div>
</div>

<div class="grid">
    <table>
        <thead>
        <tr>
            <th class="W80">순위</th>
            <th class="W250">분류</th>
            <th>상품</th>
            <th class="W100">찜횟수</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($list as $row):?>
            <tr>
                <td class="text-right"><?=number_format($row['num'])?></td>
                <td><?=$row['parent_names']?><?=$row['cat_title']?></td>
                <td><?=$row['prd_name']?></td>
                <td class="text-right"><?=number_format($row['prd_wish_count'])?></td>
            </tr>
        <?php endforeach;?>
        <?php if(count($list) == 0):?>
            <tr>
                <td colspan="4" class="empty">찜한 상품이 없습니다.</td>
            </tr>
        <?php endif;?>
        </tbody>
    </table>
</div>

<div class="text-center MT10"><?=$pagination?></div>