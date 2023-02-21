<div class="page-header" data-fit-aside>
    <h1 class="page-title">팝업 관리</h1>
</div>
<div class="ax-button-group" data-fit-aside>
    <div class="left">
        <button type="button" onclick="grid.form()" class="btn btn-primary"><i class="fal fa-plus"></i> 신규 팝업 등록</button>
    </div>
    <div class="right">
        <button type="button" onclick="grid.refresh()" class="btn btn-default"><i class="fal fa-sync"></i> 새로고침</button>
    </div>
</div>

<div class="grid-wrapper" data-fit-content>
    <div class="grid-container" id="grid-container"></div>
</div>

<script>
    var grid = new GRID('#grid-container', {
        paging: {
            pageSize: 15
        },
        columns: [
            {caption:'번호', dataField:'nums', alignment:'right', dataType:'number', format:'fixedPoint', width:80},
            {caption:'표시상태', dataField:'pop_state', alignment:'center', width:80},
            {caption:'팝업 구분', dataField:'pop_type', alignment:'center', width:80, customizeText: function(cell) { return cell.value == 'N' ? '레이어' : '새창' }},
            {caption:'팝업 이름', dataField:'pop_title', alignment:'left', minWidth:120},
            {caption:'너비 (px)', dataField:'pop_width', alignment:'right', dataType:'number', format:'fixedPoint', width:80},
            {caption:'높이 (px)', dataField:'pop_height', alignment:'right', dataType:'number', format:'fixedPoint', width:80},
            {caption:'표시 시작일', dataField:'pop_start', alignment:'center', width:160},
            {caption:'표시 종료일', dataField:'pop_end', alignment:'center', width:160},
            {caption:'최종수정자', dataField:'upd_username', alignment:'center', width:120},
            {caption:'최종수정일', dataField:'upd_datetime', alignment:'center', width:160},
        ],
        onCellPrepared: function(e) {
            if(e.rowType == 'data') {
                if(e.column.dataField == 'pop_state') {
                    var color = ( e.value == '표시중' )? '#3498db': '#e32815';
                    e.cellElement.css("color", color);
                }
            }
        },
        dataSource: new DevExpress.data.DataSource({
            key : 'pop_idx',
            load: function(loadOptions) {
                var d = $.Deferred();
                var params = grid.getSearchParam(loadOptions);

                $.ajax({
                    url : base_url + '/admin/ajax/management/popups',
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
            grid.form(e.data.pop_idx);
        },
        onContextMenuPreparing: function(e) {
            if (e.row.rowType === "data") {
                e.items = [
                    {
                      icon: 'edit',
                      text: '수정',
                      onItemClick: function () {
                        grid.form(e.row.data.pop_idx);
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
    });

    grid.form = function(pop_idx) {
        pop_idx = typeof pop_idx != 'undefined' && pop_idx ? pop_idx : '';
        APP.MODAL.callback = function() {
            grid.refresh();
            APP.MODAL.close();
        };
        APP.MODAL.open({
            iframe: {
                url: base_url + '/admin/management/popup_form/' + pop_idx,
            },
            width:800,
            height:600,
            header: {
                title:'팝업 정보 입력'
            }
        });
    };

    grid.delete = function(data) {
        if(! confirm('선택하신 팝업 정보 [' + data.pop_title + ']를 삭제하시겠습니까?')) return false;
        $.ajax({
            url: base_url + '/admin/ajax/management/popups',
            type: 'DELETE',
            data: {
                pop_idx : data.pop_idx
            },
            success:function() {
                toastr.success('팝업삭제가 완료되었습니다.');
                grid.refresh();
            }
        })
    };

    $(function() {
        grid.init();
    });
</script>