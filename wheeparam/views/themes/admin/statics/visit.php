<div class="page-header">
    <h1 class="page-title">사용자 접속 로그<small>방문통계 &gt; 사용자 접속 로그</small></h1>
</div>

<div class="box">
    <div class="box-header">
        <h4 class="box-title">검색 필터</h4>
    </div>
    <?=form_open(NULL, array('method'=>'get','class'=>'form-flex','autocomplete'=>'off'))?>
    <div data-ax-tbl class="ax-search-tbl">
        <div data-ax-tr>
            <div data-ax-td>
                <div data-ax-td-label>일자 검색</div>
                <div data-ax-td-wrap>
                    <input class="form-control form-control-sm" name="startdate" data-toggle="datepicker" value="<?=$startdate?>">
                </div>
                <div data-ax-td-wrap>
                    <input class="form-control form-control-sm" name="enddate" data-toggle="datepicker" value="<?=$enddate?>">
                </div>
            </div>
            <div data-ax-td>
                <div data-ax-td-label>접속 기기</div>
                <div data-ax-td-wrap>
                    <label class="w-check">
                        <input type="checkbox" name="is_mobile[]" value="N" <?=in_array('N', $is_mobile)?'checked':''?>><span>PC</span>
                    </label>
                    <label class="w-check">
                        <input type="checkbox" name="is_mobile[]" value="Y" <?=in_array('Y', $is_mobile)?'checked':''?>><span>모바일</span>
                    </label>
                </div>
            </div>
            <div data-ax-td>
                <div data-ax-td-label>IP 검색</div>
                <div data-ax-td-wrap>
                    <input class="form-control" name="ip" value="<?=$ip?>" placeholder="검색할 IP를 입력하세요">
                </div>
            </div>
            <div data-ax-td>
                <div data-ax-td-wrap>
                    <button class="btn btn-sm btn-default"><i class="far fa-search"></i> 필터적용</button>
                </div>
            </div>
        </div>
    </div>
    <?=form_close()?>
</div>

<div class="H10"></div>

<div class="box">
    <div class="box-header">
        <h4 class="box-title">사용자 접속 로그</h4>
        <div class="box-action">
            <button type="button" class="btn btn-sm btn-default"><i class="far fa-file-excel"></i> Excel 다운로드</button>
        </div>
    </div>

    <div data-ax5grid>
        <table>
            <thead>
            <tr>
                <th class="W155">접속일자</th>
                <th class="W100">접속 IP</th>
                <th class="W100">국가</th>
                <th class="W200">지역</th>
                <th class="W100">조직</th>
                <th class="W200">브라우져</th>
                <th class="W150">접속기기</th>
                <th class="W100">모바일</th>
                <th>리퍼러</th>
                <th class="W120">접속 검색어</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($visit_list['list'] as $row) :?>
                <tr>
                    <td class="text-center"><?=$row['sta_regtime']?></td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><?=$row['sta_ip']?></button>
                            <ul class="dropdown-menu">
                                <li><a href="">[<?=$row['sta_ip']?>] 접근금지 IP로 설정</a></li>
                            </ul>
                        </div>
                    </td>
                    <?php if($row['sta_country']) :?>
                        <td class="text-center"><?=$row['sta_country']?></td>
                        <td title="<?=$row['sta_addr']?>"><span class="ellipsis W200" style="display:block"><?=$row['sta_addr']?></span></td>
                        <td title="<?=$row['sta_org']?>"><span class="ellipsis W100" style="display:block"><?=$row['sta_org']?></span></td>
                    <?php else :?>
                        <td class="text-center" colspan="3">
                            <button type="button" class="btn btn-xs btn-default" data-button="get-ip-info" data-ip="<?=$row['sta_ip']?>"><i class="far fa-search"></i> 확인</button>
                        </td>
                    <?php endif;?>
                    <td class="text-center"><?=$row['sta_browser']?></td>
                    <td class="text-center"><?=$row['sta_device']?></td>
                    <td class="text-center text-primary"><?=$row['sta_is_mobile']=='Y'?'<i class="far fa-check"></i>':''?></td>
                    <td>
                        <?php if($row['sta_referrer_host']) : ?>
                            <a href="<?=$row['sta_referrer']?>" title="<?=$row['sta_referrer']?>" target="_blank"><?=$row['sta_referrer_host']?></a>
                        <?php endif;?>
                    </td>
                    <td><?=$row['sta_keyword']?></td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>

    <div class="bottom-actions MT20">
        <div class="center">
            <?=$pagination?>
        </div>
    </div>
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