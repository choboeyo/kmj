<div class="page-header" data-fit-aside>
    <h1 class="page-title">SMS/카카오알림톡 발송로그</h1>
</div>

<form>
    <div data-ax-tbl class="ax-search-tbl">
        <div data-ax-tr>
            <div data-ax-td class="W350">
                <div data-ax-td-label>일자 검색</div>
                <div data-ax-td-wrap>
                    <input class="form-control" name="startdate" data-toggle="datepicker" data-chained-datepicker="[name='enddate']" value="<?=date('Y-m-d', strtotime("-1 month", time()))?>">
                </div>
                <div data-ax-td-wrap>
                    <input class="form-control" name="enddate" data-toggle="datepicker" value="<?=date('Y-m-d')?>">
                </div>
            </div>
            <div data-ax-td>
                <div data-ax-td-wrap>
                    <button class="btn btn-default"><i class="fal fa-search"></i> 필터적용</button>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="H15"></div>
<div class="grid">
    <table style="table-layout: fixed">
        <thead>
        <tr>
            <th class="W150">일시</th>
            <th class="W80">구분</th>
            <th class="W100">수신처</th>
            <th class="W80">결과</th>
            <th>내용</th>
            <th class="W250">메시지</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($list as $row):?>
        <tr>
            <td class="text-center"><?=date('Y.m.d H:i:s', strtotime($row['sml_regtime']))?></td>
            <td class="text-center"><?=$row['sml_type']?></td>
            <td class="text-center"><?=$row['sml_phone']?></td>
            <td class="text-center <?=$row['sml_result']!='성공'?'text-danger':''?>"><?=$row['sml_result']?></td>
            <td style="word-break: break-all;"><?=$row['sml_content']?></td>
            <td style="word-break: break-all;"><?=$row['sml_message']?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<div class="H15"></div>
<div class="text-center"><?=$pagination?></div>