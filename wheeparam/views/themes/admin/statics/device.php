<?=$this->site->add_js("https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js")?>
<?=$this->site->add_js("https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.min.js")?>
<div class="page-header">
    <h1 class="page-title">PC / Mobile 접속 통계</h1>
</div>

<?=form_open(NULL, array('method'=>'get','class'=>'form-flex','autocomplete'=>'off'))?>
<div data-ax-tbl class="ax-search-tbl">
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>일자 검색</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="startdate" data-toggle="datepicker" data-chained-datepicker="[name='enddate']" value="<?=$startdate?>">
            </div>
            <div data-ax-td-wrap>
                <input class="form-control" name="enddate" data-toggle="datepicker" value="<?=$enddate?>">
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-wrap>
                <button class="btn btn-sm btn-default"><i class="fal fa-search"></i> 필터적용</button>
            </div>
        </div>
    </div>
</div>
<?=form_close()?>
<div class="H10"></div>

<div class="H10"></div>

<div class="row">
    <div class="col-sm-6">
        <div style="max-width:400px;margin:auto">
            <h4 class="text-center">PC/모바일 접속 통계</h4>
            <canvas id="chart-device" width="200" height="200"></canvas>
        </div>
        <div class="H30"></div>
        <div style="max-width:400px;margin:auto">
            <h4 class="text-center">모바일 기기별 통계</h4>
            <canvas id="chart-mobile-device" width="200" height="200"></canvas>
        </div>

    </div>
    <div class="col-sm-6">
        <h4>기기별 통계</h4>
        <div class="grid">
            <table>
                <thead>
                <tr>
                    <th class="text-center">기기</th>
                    <th class="text-center">접속 수</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="text-center">PC</td>
                    <td class="text-right"><?=number_format($statics['sum']['pc']['count'])?> (<?=$statics['sum']['mobile']['count']?>%)</td>
                </tr>
                <tr>
                    <td class="text-center">Mobile</td>
                    <td class="text-right"><?=number_format($statics['sum']['mobile']['count'])?> (<?=$statics['sum']['mobile']['count']?>%)</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="H30"></div>
        <h4>일자별 통계</h4>
        <div class="grid">
            <table>
                <thead>
                <tr>
                    <th class="text-center">일자</th>
                    <th class="text-center">PC</th>
                    <th class="text-center">Mobile</th>
                    <th class="text-center">Total</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($statics['list'] as $date=>$row):?>
                <tr>
                    <td class="text-center"><?=$date?></td>
                    <td class="text-right"><?=number_format($row['pc'])?></td>
                    <td class="text-right"><?=number_format($row['mobile'])?></td>
                    <td class="text-right"><?=number_format($row['total'])?></td>
                </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="H30"></div>

<script>
    $(function(){
        var $chart = $("#chart-device");
        var $chart_mobile = $("#chart-mobile-device");
        var chart = new Chart($chart, {
            type: 'pie',
            data: {
                labels: ["PC", "Mobile"],
                datasets: [{
                    label : '# %',
                    data: [ <?=$statics['sum']['pc']['count']?>, <?=$statics['sum']['mobile']['count']?>],
                    backgroundColor : ['rgb(255, 99, 132)','rgb(54, 162, 235)']
                }]
            },
            options : {
                animation : {
                    animateScale:true
                },
                legend: {
                    labels : {
                        fontColor : '#fff'
                    }
                }
            }
        });
        var mobile_chart_data = <?=$statics['device_counts']?>;
        var mobile_chart = new Chart($chart_mobile, {
            type: 'pie',
            data: {
                labels: <?=$statics['device_list']?>,
                datasets: [{
                    data: mobile_chart_data,
                    backgroundColor : randomColorGenerator(mobile_chart_data.length)
                }]
            },
            options : {
                animation : {
                    animateScale:true
                },

                legend: {
                    labels : {
                        fontColor : '#fff'
                    }
                }
            }
        });
    });

    var randomColorGenerator = function (count) {
        var ret = [];
        for(i=0; i<count;i++)
        {
            ret.push( '#' + (Math.random().toString(16) + '0000000').slice(2, 8) );
        }
        return ret;
    };

</script>