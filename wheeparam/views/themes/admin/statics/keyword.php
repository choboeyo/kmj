<?=$this->site->add_js("https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js")?>
<?=$this->site->add_js("https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.min.js")?>
<div class="page-header">
    <h1 class="page-title">키워드별 접속 통계</h1>
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
        <div style="max-width:400px;margin:auto">
            <h4 class="text-center">키워드별 접속 통계</h4>
            <canvas id="chart-browser" width="200" height="200"></canvas>
        </div>
    </div>
    <div class="col-sm-6">
        <div data-ax5grid>
            <table>
                <thead>
                <tr>
                    <th>키워드</th>
                    <th>접속수</th>
                </tr>
                </thead>
                <tbody>
                 <?php foreach($statics['list'] as $row):?>
                <tr>
                    <td class="text-center"><?=$row['sta_keyword']?></td>
                    <td class="text-right"><?=number_format($row['count'])?> (<?=$statics['total']>0? round($row['count']/$statics['total']*100,2):0?>%)</td>
                </tr>
                <?php endforeach;?>
                <?php if(count($statics['list']) == 0):?>
                <tr>
                    <td class="empty" colspan="2">키워드로 접속한 기록이 없습니다.</td>
                </tr>
                <?php endif;?>
                </tbody>
                <tfoot>
                <tr>
                    <td class="text-center">TOTAL</td>
                    <td class="text-right"><?=number_format($statics['total'])?></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


<script>
    $(function(){
        var $chart = $("#chart-browser");
        var chart_data = <?=$statics['counts']?>;
        var chart = new Chart($chart, {
            type: 'pie',
            data: {
                labels: <?=$statics['sta_keyword']?>,
                datasets: [{
                    label : '# %',
                    data: chart_data,
                    backgroundColor : randomColorGenerator(chart_data.length)
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
