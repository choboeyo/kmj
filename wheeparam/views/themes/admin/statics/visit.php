<div class="page-header">
    <h1 class="page-title">사용자 접속 로그</h1>
</div>

<?=form_open(NULL, array('method'=>'get','class'=>'form-flex','autocomplete'=>'off'))?>
<div class="form-group">
    <label class="control-label">일자 검색</label>
    <div class="controls">
        <input class="form-control form-control-inline" name="startdate" data-toggle="datepicker" value="<?=$startdate?>">
        <input class="form-control form-control-inline" name="enddate" data-toggle="datepicker" value="<?=$enddate?>">
        <button class="btn btn-default"><i class="far fa-search"></i> 필터적용</button>
    </div>
</div>
<?=form_close()?>

<div class="H10"></div>

<div data-ax5grid>
    <table>
        <thead>
        <tr>
            <th>접속일자</th>
            <th>국가</th>
            <th class="W100">지역</th>
            <th class="W100">조직</th>
            <th>브라우져</th>
            <th>접속기기</th>
            <th class="W100">모바일</th>
            <th class="col-sm-3">리퍼러</th>
            <th>접속 검색어</th>
            <th>접속 IP</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($visit_list['list'] as $row) :?>
            <tr>
                <td class="text-center"><?=$row['sta_regtime']?></td>
                <?php if($row['sta_country']) :?>
                <td class="text-center"><?=$row['sta_country']?></td>
                <td class="text-center" title="<?=$row['sta_addr']?>"><span class="ellipsis W100" style="display:block"><?=$row['sta_addr']?></span></td>
                <td class="text-center" title="<?=$row['sta_org']?>"><span class="ellipsis W100" style="display:block"><?=$row['sta_org']?></span></td>
                <?php else :?>
                <td class="text-center" colspan="3">
                    <button type="button" class="btn btn-default" data-button="get-ip-info" data-ip="<?=$row['sta_ip']?>"><i class="far fal fas fa-search"></i> 확인</button>
                </td>
                <?php endif;?>
                <td class="text-center"><?=$row['sta_browser']?></td>
                <td class="text-center"><?=$row['sta_device']?></td>
                <td class="text-center"><?=$row['sta_is_mobile']?></td>
                <td>
                    <?php if($row['sta_referrer_host']) : ?>
                        <a href="<?=$row['sta_referrer']?>" title="<?=$row['sta_referrer']?>" target="_blank"><?=$row['sta_referrer_host']?></a>
                    <?php endif;?>
                </td>
                <td><?=$row['sta_keyword']?></td>
                <td class="text-center"><?=$row['sta_ip']?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<div class="H10"></div>

<div class="text-center">
    <?=$pagination?>
</div>

<div class="H30"></div>

<script>
    $(function(){
        $('[data-button="get-ip-info"]').click(function(e){
            e.preventDefault();

            var ip = $(this).data('ip');
            $.ajax({
                url : '/ajax/tools/ip_info',
                type : 'POST',
                async:false,
                cache:false,
                data : {
                    ip : ip
                },
                success:function(res) {
                    location.reload();
                }
            })
        });
    });
</script>