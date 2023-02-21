<div class="page-header" data-fit-aside>
    <h1 class="page-title">연혁 관리</h1>
</div>


<div class="H10" data-fit-aside></div>

<div class="ax-button-group" data-fit-aside>
    <div class="left">
        <button type="button" onclick="grid.form()" class="btn btn-primary"><i class="fal fa-plus"></i> 신규 연혁 등록</button>
    </div>
    <div class="right">
        <p>등록된 연혁을 마우스 우클릭하여 [수정],[삭제]할 수 있습니다.</p>
    </div>
</div>
<div class="grid-wrapper" data-fit-content>
    <div class="grid-container" id="grid-container"></div>
</div>

<script>
    var grid = new GRID('#grid-container', {
        paging: false,
        columns: [
            {caption:'연도', dataField:'his_year', alignment:'center', width:80},
            {caption:'월', dataField:'his_month', alignment:'center', width:80, customizeText:function(cell) { return cell.value.zf(2) }},
            {caption:'내용', dataField:'his_content', alignment:'left', minWidth:120},
            {caption:'수정자', dataField:'upd_user_name', alignment:'center', width:100},
            {caption:'수정일', dataField:'upd_datetime', alignment:'center', width:160, dataType:'datetime', format:'yyyy-MM-dd HH:mm'},
        ],
        onCellPrepared: function(e) {
            if(e.rowType == 'data') {
                if(e.column.dataField == 'his_status') {
                    var color = ( e.value == '표시중' )? '#3498db': '#e32815';
                    e.cellElement.css("color", color);
                }
            }
        },
        dataSource: new DevExpress.data.DataSource({
            key : 'his_idx',
            load: function(loadOptions) {
                var d = $.Deferred();
                var params = grid.getSearchParam(loadOptions);

                $.ajax({
                    url : base_url + '/admin/ajax/management/history',
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
        onRowDblClick: function(e) {
            grid.form(e.data.his_idx);
        },
        onContextMenuPreparing: function(e) {
            if (e.row.rowType === "data") {
                e.items = [
                    {
                        icon: 'edit',
                        text: '수정',
                        onItemClick: function () {
                            grid.form(e.row.data.his_idx);
                        }
                    },
                    {
                        icon : 'trash',
                        text: "삭제",
                        onItemClick: function () {
                            grid.delete(e.row.data);
                        }
                    }
                ]
            }
        },
        filter: function () {
        },
    });

    grid.form = function(his_idx) {
        his_idx = typeof his_idx != 'undefined' && his_idx ? his_idx : '';
        APP.MODAL.callback = function() {
            grid.refresh();
            APP.MODAL.close();
        };
        APP.MODAL.open({
            iframe: {
                url: base_url + '/admin/management/history_form/' + his_idx,
            },
            width:800,
            height:400,
            header: {
                title:'연혁 정보 입력'
            }
        });
    };
    /* 연혁 삭제 */
    grid.delete = function(data) {
        if(! confirm('선택하신 연혁 정보를 삭제하시겠습니까?')) return false;
        $.ajax({
            url: base_url + 'admin/ajax/management/history',
            type: 'DELETE',
            data: {
                his_idx : data.his_idx
            },
            success:function() {
                toastr.success('연혁삭제가 완료되었습니다.');
                grid.refresh();
            }
        })
    };

    $(function() {
        grid.init();
    });

</script>