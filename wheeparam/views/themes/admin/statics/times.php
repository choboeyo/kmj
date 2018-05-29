<?=$this->site->add_js("https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js")?>
<?=$this->site->add_js("https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.min.js")?>
<div class="page-header">
    <h1 class="page-title">방문 시간별 접속 통계</h1>
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

<div class="row">

    <div class="col-sm-6">
        <div>
            <h4 class="text-center">방문 시간별 접속 통계</h4>
            <canvas id="chart-browser" width="200" height="200"></canvas>
        </div>
    </div>
    <div class="col-sm-6">
        <div data-ax5grid>
            <table>
                <thead>
                <tr>
                    <th>시간대</th>
                    <th>접속수</th>
                    <th>백분율(%)</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($statics['list'] as $key=>$value):?>
                    <tr>
                        <td class="text-center"><?=$key?>시</td>
                        <td class="text-right"><?=number_format($value)?></td>
                        <td class="text-right"><?=$statics['total']>0? round($value/$statics['total']*100,2):0?>%</td>
                    </tr>
                <?php endforeach;?>
                <?php if(count($statics['list']) == 0):?>
                    <tr>
                        <td class="empty" colspan="3">키워드로 접속한 기록이 없습니다.</td>
                    </tr>
                <?php endif;?>
                </tbody>
                <tfoot>
                <tr>
                    <td class="text-center">TOTAL</td>
                    <td class="text-right"><?=number_format($statics['total'])?></td>
                    <td class="text-right">100%</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


<script>
    $(function(){
        var $chart = $("#chart-browser");
        var chart_data = <?=$statics['valuelist']?>;
        var chart = new Chart($chart, {
            type: 'line',
            data: {
                labels: <?=$statics['hourlist']?>,
                datasets: [{
                    label : '방문자수',
                    data: chart_data,
                    backgroundColor : 'rgba(0,0,0,0.5)',
                    borderColor : 'rgba(0,0,0,0.15)',
                    borderWidth : '1px',
                    pointBackgroundColor : '#cc7b19'
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
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            fontColor : '#fff'
                        }
                    }],
                    xAxes: [{
                        ticks: {
                            fontColor : '#fff'
                        }
                    }],
                }
            }
        });
    });
</script>
