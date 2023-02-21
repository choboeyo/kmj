<div class="page-header" data-fit-aside>
    <h2 class="page-title"><?=$this->site->config('point_name')?> 관리</h2>
</div>

<form data-grid-search onsubmit="grid.refresh(1);return false;" data-fit-aside autocomplete="off">
    <div data-ax-tbl>
        <div data-ax-tr>
            <div data-ax-td>
                <div data-ax-td-label><?=$this->site->config('point_name')?> 유형</div>
                <div data-ax-td-wrap>
                    <select class="form-control form-control-inline" name="target_type">
                        <option value="">전체보기</option>
                        <?php foreach($point_type as $key=>$val) :?>
                            <option value="<?=$key?>" <?=$target_type==$key?'selected':''?>><?=$val?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <div data-ax-td>
                <div data-ax-td-label>기간검색</div>
                <div data-ax-td-wrap>
                    <input class="form-control" data-chained-datepicker="[name='enddate']" name="startdate" data-toggle="datepicker" value="">
                </div>
                <div data-ax-td-wrap>
                    <input class="form-control" name="enddate" data-toggle="datepicker" value="">
                </div>
            </div>
            <div data-ax-td class="W120">
                <div data-ax-td-wrap>
                    <button class="btn btn-default"><i class="fal fa-search"></i> 필터적용</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="H10" data-fit-aside></div>

<div class="grid-wrapper" data-fit-content>
    <div class="grid-container" id="grid-container"></div>
</div>

<script>
    var grid = new GRID('#grid-container', {
        paging: {
            pageSize: 20
        },
        columns: [
            {caption:'번호', dataField:'nums', alignment:'right', width:60, dataType:'number', format:'fixedPoint'},
            {caption:'일시', dataField:'reg_datetime', alignment:'center', width:120},
            {caption:'회원명', dataField:'mem_nickname', alignment:'center', width:80},
            {caption:'회원ID', dataField:'mem_userid', alignment:'left', width:180},
            {caption:'구분', dataField:'target_type', alignment:'center', width:120},
            {
                caption: '증가', name:'mpo_value_plus', dataField:'mpo_value', alignment:'right', dataType:'number', format:'fixedPoint', width:120,
                calculateCellValue: function(cell) {
                    if(cell.mpo_flag == 1) return cell.mpo_value.numberFormat()
                }
            },
            {
                caption: '감소',name:'mpo_value_minus', dataField:'mpo_value', alignment:'right', dataType:'number', format:'fixedPoint', width:120,
                calculateCellValue: function(cell) {
                    if(cell.mpo_flag == -1) return cell.mpo_value.numberFormat()
                }
            },
            {caption:'내용', dataField:'mpo_description', alignment:'left', minWidth:120},
        ],
        onCellPrepared: function(e) {
            if(e.rowType == 'data') {
                if(e.column.name == 'mpo_value_plus') {
                    e.cellElement.css("color", '#3498db');
                }
                else if (e.column.name == 'mpo_value_minus') {
                    e.cellElement.css("color", '#e32815');
                }
            }
        },
        onContextMenuPreparing: function(e) {
            if (e.row.rowType === "data") {
                e.items = APP.memberMenuObject(e, <?=$this->site->config('point_use')=='Y'?'"'.$this->site->config('point_name').'"':'false'?>);
            }
        },
        dataSource: new DevExpress.data.DataSource({
            key : 'mpo_idx',
            load: function(loadOptions) {
                var d = $.Deferred();
                var params = grid.getSearchParam(loadOptions);

                $.ajax({
                    url : base_url + '/admin/ajax/members/points',
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