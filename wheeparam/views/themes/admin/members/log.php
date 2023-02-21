<div class="page-header" data-fit-aside>
    <h1 class="page-title">회원 로그인 기록<small>회원 관리 &gt; 회원 로그인 기록</small></h1>
</div>

<form data-grid-search onsubmit="grid.refresh(1);return false;" data-fit-aside autocomplete="off">
    <div data-ax-tbl>
        <div data-ax-tr>
            <div data-ax-td>
                <div data-ax-td-label>기간 검색</div>
                <div data-ax-td-wrap>
                    <input class="form-control" data-chained-datepicker="[name='enddate']" name="startdate" data-toggle="datepicker" value="<?=date('Y-m-d',strtotime('-1 month'))?>">
                </div>
                <div data-ax-td-wrap>
                    <input class="form-control" name="enddate" data-toggle="datepicker" value="<?=date('Y-m-d')?>">
                </div>
            </div>
            <div data-ax-td>
                <div data-ax-td-label>검색어 입력</div>
                <div data-ax-td-wrap>
                    <select class="form-control form-control-inline" name="sc">
                        <option value="userid" <?=$sc=='userid'?'selected':''?>>아이디</option>
                        <option value="nickname" <?=$sc=='nickname'?'selected':''?>>닉네임</option>
                        <option value="idx" <?=$sc=='idx'?'selected':''?>>회원번호</option>
                    </select>
                </div>
            </div>
            <div data-ax-td>
                <div data-ax-td-wrap>
                    <input class="form-control" name="st" value="<?=$st?>">
                </div>
                <div data-ax-td-wrap>
                    <button class="btn btn-default"><i class="fal fa-search"></i> 필터적용</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="H10" data-fit-aside></div>

<div class="grid-wrapper" data-fit-content>
    <div id="grid-container" class="grid-wrapper"></div>
</div>

<script>
    var grid = new GRID('#grid-container', {
        paging: {
          pageSize:15
        },
        columns: [
            {caption:'순번', dataField:'nums', alignment:'right', width:80, dataType:'number', format:'fixedPoint'},
            {caption:'일시', dataField:'mlg_regtime', alignment:'center', width:160},
            {caption:'아이디', dataField:'mem_userid', alignment:'left', width:160},
            {caption:'이름', dataField:'mem_nickname', alignment:'left', width:100},
            {caption:'브라우져', dataField:'mlg_browser', alignment:'left', width:80},
            {caption:'OS', dataField:'mlg_platform', alignment:'left', width:150},
            {caption:'모바일', dataField:'mlg_is_mobile', alignment:'center', width:60},
            {caption:'IP', dataField:'mlg_ip', alignment:'center', width:120},
            {caption:'', calculateCellValue:function(e) {return ''}}
        ],
        dataSource: new DevExpress.data.DataSource({
            key : 'mlg_idx',
            load: function(loadOptions) {
                var d = $.Deferred();
                var params = grid.getSearchParam(loadOptions);

                $.ajax({
                    url : base_url + '/admin/ajax/members/logs',
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
    })
    $(function() {
        grid.init();
    });
</script>
