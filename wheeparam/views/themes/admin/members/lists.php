<div class="page-header" data-fit-aside>
    <h1 class="page-title">회원 목록<small>회원관리 &gt; 회원목록</small></h1>
</div>

<form data-grid-search onsubmit="grid.refresh(1);return false;" data-fit-aside autocomplete="off">
    <div data-ax-tbl>
        <div data-ax-tr>
            <div data-ax-td>
                <div data-ax-td-label>기간 검색</div>
                <div data-ax-td-wrap>
                    <select class="form-control" name="sdate">
                        <option value="regtime">가입일</option>
                        <option value="logtime">최근로그인</option>
                    </select>
                </div>
            </div>
            <div data-ax-td>
                <div data-ax-td-wrap>
                    <input class="form-control" data-chained-datepicker="[name='enddate']" name="startdate" data-toggle="datepicker" value="">
                </div>
                <div data-ax-td-wrap>
                    <input class="form-control" name="enddate" data-toggle="datepicker" value="">
                </div>
            </div>
            <div data-ax-td>
                <div data-ax-td-label>검색어 입력</div>
                <div data-ax-td-wrap>
                    <select class="form-control" name="sc">
                        <option value="mem_nickname">닉네임</option>
                        <option value="mem_userid">아이디</option>
                    </select>
                </div>
            </div>
            <div data-ax-td>
                <div data-ax-td-wrap>
                    <input class="form-control" name="st">
                </div>
                <div data-ax-td-wrap>
                    <button class="btn btn-default"><i class="fal fa-search"></i> 필터적용</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="H10" data-fit-aside></div>

<div class="ax-button-group" data-fit-aside>
    <div class="left">
        <a class="btn btn-primary" href="<?=base_url('admin/members/add')?>"><i class="fal fa-user-plus"></i> 회원 추가</a>
    </div>
</div>

<div class="grid-wrapper" data-fit-content>
    <div id="grid-container" class="grid-container"></div>
</div>

<script>
    var grid = new GRID('#grid-container', {
        columns: [
            {caption:'순번', dataField:'nums', alignment:'right', dataType:'number', format:'fixedPoint', width:60},
            {caption:'상태', dataField:'mem_status', alignment:'center', width:80,
                customizeText:function(cell){
                    if(cell.value == 'Y') return '정상';
                    else if (cell.value == 'D') return '접근금지';
                    else if (cell.value == 'H') return '휴면';
                    else if (cell.value == 'N') return '탈퇴';
                }
            },
            {caption:'아이디', dataField:'mem_userid', alignment:'left', width:150},
            {caption:'닉네임', dataField:'mem_nickname', alignment:'left', width:150},
            {caption:'E-mail', dataField:'mem_email', alignment:'left', width:150},
            {caption:'레벨', dataField:'mem_auth', alignment:'right', width:60, dataType:'number', format:'fixedPoint'},
            {caption:'<?=$this->site->config('point_name')?>', dataField:'mem_point', alignment:'right', width:80, dataType:'number', format:'fixedPoint'},
            {caption:'EMAIL', dataField:'mem_recv_email', alignment:'center', width:60,
                customizeText: function(cell) {return cell.value == 'Y'?'수신':'미수신'}
            },
            {caption:'SMS', dataField:'mem_recv_sms', alignment:'center', width:60,
                customizeText: function(cell) {return cell.value == 'Y'?'수신':'미수신'}
            },
            {caption:'가입일시', dataField:'mem_regtime', alignment:'center', width:150},
            {caption:'가입IP', dataField:'mem_regip', alignment:'center', width:150},
            {caption:'최근로그인', dataField:'mem_logtime', alignment:'center', width:150},
            {caption:'최근IP', dataField:'mem_logip', alignment:'center', width:150},
            {caption:'', calculateCellValue:function(e) {return ''}}
        ],
        onContextMenuPreparing: function(e) {
            if (e.row.rowType === "data") {
                e.items = APP.memberMenuObject(e, <?=$this->site->config('point_use')=='Y'?'"'.$this->site->config('point_name').'"':'false'?>);
            }
        },
        dataSource: new DevExpress.data.DataSource({
            key : 'mem_idx',
            load: function(loadOptions) {
                var d = $.Deferred();
                var params = grid.getSearchParam(loadOptions);

                $.ajax({
                    url : base_url + '/admin/ajax/members',
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