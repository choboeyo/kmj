<div class="page-header">
<h1 class="page-title"><?=$mem['mem_nickname']?>님의 <?=$this->site->config('point_name')?> 정보</h1>
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
            <div data-ax-td class="W100">
                <div data-ax-td-wrap>
                    <button class="btn btn-default"><i class="fal fa-search"></i> 필터적용</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="H10"></div>
<div class="ax-button-group">
    <div class="left">
        <button type="button" class="btn btn-default" onclick="APP.MEMBER.POP_POINT_FORM_ADMIN('<?=$mem['mem_idx']?>');"><i class="fal fa-plus-circle"></i> <?=$this->site->config('point_name')?> 등록</button>
    </div>
</div>
<div id="grid-container"></div>

<script>
    var grid = new GRID('#grid-container', {
        paging: {
            pageSize: 10
        },
        columns: [
            {caption:'번호', dataField:'nums', alignment:'right', width:60, dataType:'number', format:'fixedPoint'},
            {caption:'일시', dataField:'reg_datetime', alignment:'center', width:120},
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
        dataSource: new DevExpress.data.DataSource({
            key : 'mpo_idx',
            load: function(loadOptions) {
                var d = $.Deferred();
                var params = grid.getSearchParam(loadOptions);
                params.mem_idx = '<?=$mem['mem_idx']?>';

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