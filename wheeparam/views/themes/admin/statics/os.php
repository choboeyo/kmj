<?=$this->site->add_js("https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js")?>
<?=$this->site->add_js("https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.min.js")?>
<div class="page-header">
    <h1 class="page-title">OS별 접속 통계</h1>
</div>

<?=form_open(NULL, array('method'=>'get','class'=>'form-flex','autocomplete'=>'off'))?>
<div data-ax-tbl class="ax-search-tbl">
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>일자 검색</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="startdate" data-toggle="datepicker" value="<?=$startdate?>">
            </div>
            <div data-ax-td-wrap>
                <input class="form-control" name="enddate" data-toggle="datepicker" value="<?=$enddate?>">
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
<div class="H10"></div>

<div class="row">
    <div class="col-sm-6">
        <div style="max-width:400px;margin:auto">
            <h4 class="text-center">OS별 접속 통계</h4>
            <canvas id="chart-os" width="200" height="200"></canvas>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="grid">
            <table>
                <thead>
                <tr>
                    <th>OS</th>
                    <th>접속수</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($statics['list'] as $row):?>
                <tr>
                    <td class="text-center"><?=$row['sta_platform']?></td>
                    <td class="text-right"><?=number_format($row['count'])?> (<?=$statics['total']>0? round($row['count']/$statics['total']*100,2):0?>%)</td>
                </tr>
                <?php endforeach;?>
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
        var $chart = $("#chart-os");
        var chart_data = <?=$statics['counts']?>;
        var chart = new Chart($chart, {
            type: 'pie',
            data: {
                labels: <?=$statics['sta_platform']?>,
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
