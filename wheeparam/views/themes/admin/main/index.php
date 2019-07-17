<?=$this->site->add_js("https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js")?>
<?=$this->site->add_js("https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.min.js")?>

<div class="page-header">
    <h1 class="page-title">DASHBOARD</h1>
</div>

<div class="row">

    <div class="col-sm-8">
        <h4 class="text-center">최근 방문자</h4>
        <canvas id="chart-recent"></canvas>
    </div>

    <script>
        $(function(){
            var chartData = <?=$month_data?>;
            var chartDataMobile = <?=$month_mobile?>;
            var chartLabel = <?=$month_label?>;
            var myBarChart = new Chart($('#chart-recent'), {
                type: 'bar',
                data: {
                    labels: chartLabel,
                    datasets: [{
                        label : 'PC',
                        backgroundColor : 'rgba(255,255,255,0.8)',
                        data: chartData
                    },{
                        label : 'Mobile',
                        backgroundColor : 'rgba(0,0,0,0.4)',
                        data : chartDataMobile
                    }]
                },
                options : {
                    responsive : true,
                    scales: {
                        xAxes: [{
                            stacked: true,
                            ticks: {
                                fontColor : '#fff'
                            }
                        }],
                        yAxes : [{
                            stacked:true,
                            ticks: {
                                fontColor : '#fff'
                            }
                        }]
                    }
                }
                //options: options
            });
        });
    </script>

    <div class="col-sm-4">
        <div class="ax-button-group">
            <div class="left">
                <h4>최근 방문자 통계</h4>
            </div>
        </div>
        <div class="grid">
            <table>
                <thead>
                <tr>
                    <th>구분</th>
                    <th>방문자</th>
                    <th>PC</th>
                    <th>Mobile</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="text-center">TOTAL</td>
                    <td class="text-right"><?=number_format($total_count['sumT'])?></td>
                    <td class="text-right"><?=number_format($total_count['sumT'] - $total_count['sumM'])?></td>
                    <td class="text-right"><?=number_format($total_count['sumM'])?></td>
                </tr>
                <tr>
                    <td class="text-center">최근 30일</td>
                    <td class="text-right"><?=number_format($month_count['sumT'])?></td>
                    <td class="text-right"><?=number_format($month_count['sumT'] - $month_count['sumM'])?></td>
                    <td class="text-right"><?=number_format($month_count['sumM'])?></td>
                </tr>
                <tr>
                    <td class="text-center">오늘</td>
                    <td class="text-right"><?=number_format($today_count['sumT'])?></td>
                    <td class="text-right"><?=number_format($today_count['sumT'] - $today_count['sumM'])?></td>
                    <td class="text-right"><?=number_format($today_count['sumM'])?></td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="H30"></div>

        <div class="ax-button-group">
            <div class="left">
                <h4>회원 통계</h4>
            </div>
        </div>
        <div class="grid">
            <table>
                <thead>
                <tr>
                    <th class="W100">구분</th>
                    <th>회원수</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="text-center">TOTAL</td>
                    <td class="text-right"><?=number_format($total_member)?></td>
                </tr>
                <tr>
                    <td class="text-center">로그인 금지</td>
                    <td class="text-right"><?=number_format($total_member_d)?></td>
                </tr>
                <tr>
                    <td class="text-center">휴면</td>
                    <td class="text-right"><?=number_format($total_member_h)?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>