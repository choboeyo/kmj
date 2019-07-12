<div class="page-header" data-fit-aside>
    <h1 class="page-title">사용자 접속 로그<small>방문통계 &gt; 사용자 접속 로그</small></h1>
</div>

<form data-grid-search onsubmit="grid.refresh(1);return false;" data-fit-aside>
<div data-ax-tbl class="ax-search-tbl" data-grid-search>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>일자 검색</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="startdate" data-toggle="datepicker" data-chained-datepicker="[name='enddate']" value="<?=date('Y-m-d', strtotime("-1 month", time()))?>">
            </div>
            <div data-ax-td-wrap>
                <input class="form-control" name="enddate" data-toggle="datepicker" value="<?=date('Y-m-d')?>">
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>접속 기기</div>
            <div data-ax-td-wrap>
                <select class="form-control" name="is_mobile">
                    <option value="">전체보기</option>
                    <option value="N">PC</option>
                    <option value="Y">모바일</option>
                </select>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>IP 검색</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="ip" value="" placeholder="검색할 IP를 입력하세요">
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-wrap>
                <button class="btn btn-sm btn-default"><i class="far fa-search"></i> 필터적용</button>
            </div>
        </div>
    </div>
</div>
</form>
<div class="H10" data-fit-aside></div>
<div class="grid-wrapper">
    <div class="gird-container" id="grid-container"></div>
</div>

<script>
    var grid = new GRID('#grid-container', {
        paging: {
          pageSize:20
        },
        columns: [
            {caption:'순번', width:80, dataField: 'nums', alignment:'right', dataType:'number', format:'fixedPoint'},
            {caption:'접속일자', width:150, dataField: 'sta_regtime', alignment:'center'},
            {caption:'접속IP', width:100, dataField: 'sta_ip', alignment:'center'},
            {caption:'브라우저', width:120, dataField: 'sta_browser', alignment:'left'},
            {caption:'접속기기', width:150, dataField: 'sta_device', alignment:'left'},
            {caption:'모바일', width:50, dataField: 'sta_is_mobile', alignment:'center'},
            {caption:'리퍼러 호스트', width:200, dataField: 'sta_referrer_host', alignment:'left'},
            {caption:'리퍼러', minWidth:100, dataField: 'sta_referrer', alignment:'left'},
            {caption:'리퍼러 키워드', width:150, dataField: 'sta_keyword', alignment:'left'},
        ],
        dataSource: new DevExpress.data.DataSource({
            key : 'sta_idx',
            load: function(loadOptions) {
                var d = $.Deferred();
                var params = grid.getSearchParam(loadOptions);

                $.ajax({
                    url : base_url + '/admin/ajax/statics/visit',
                    type: 'GET',
                    async: false,
                    cache: false,
                    data: params
                }).done(function(res) {
                    d.resolve(res.lists, {
                        totalCount : res.totalCount
                    });
                });

                return d.promise();
            }
        }),
    });
    $(function() {
        grid.init();
    })
</script>