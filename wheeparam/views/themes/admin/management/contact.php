<div class="page-header" data-fit-aside>
    <h1 class="page-title">상담 관리</h1>
</div>

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
            {caption:'상담의뢰인', dataField:'con_name', alignment:'center', width:120},
            {caption:'연락처', dataField:'con_phone', alignment:'center', width:120},
            {caption:'이메일', dataField:'con_email', alignment:'center', width:120},
            {caption:'메모', dataField:'con_memo', alignment:'left', width: 1000},
            {caption:'상담 희망일자', dataField:'con_date', alignment:'center', width:150, customizeText:function(cell) {return cell.value != '0000-00-00 00:00:00' ?cell.value:''}},
            {caption:'상담 신청일자', dataField:'reg_datetime', alignment:'center', width:150, customizeText:function(cell) {return cell.value != '0000-00-00 00:00:00' ?cell.value:''}}
        ],
        dataSource: new DevExpress.data.DataSource({
            key : 'con_id',
            load: function(loadOptions) {
                var d = $.Deferred();
                var params = grid.getSearchParam(loadOptions);

                $.ajax({
                    url : base_url + '/admin/ajax/management/contact',
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
        onContextMenuPreparing: function(e) {
            if (e.row.rowType === "data") {
                e.items = [
                    {
                        icon : 'trash',
                        text: "삭제",
                        onItemClick: function () {
                            grid.delete(e.row.data);
                        }
                    },
                    {
                        icon : 'edit',
                        text: "상세보기",
                        onItemClick: function () {
                            grid.edit(e.row.data);
                        }
                    }

                ]
            }
        },
        onRowDblClick: function(e) {
            grid.form(e.data.con_id);
        },
    });

    grid.delete = function(data) {
        if(! confirm('선택하신 데이타를 삭제하시겠습니까?')) return false;

        $.ajax({
            url: base_url + '/admin/ajax/management/contact',
            type: 'DELETE',
            data: {
                con_id: data.con_id
            },
            success:function() {
                toastr.success('삭제되었습니다.');
                grid.refresh();
            }
        })
    }


    grid.edit = function(data) {
        APP.MODAL.open({
            iframe : {
                url : base_url + '/admin/management/contact_form',
                param : {
                    con_id : data.con_id,
                }
            },
            width: 500,
            height: 350,
            header : {
                title : '상담 신청 정보'
            }
        });
    }



    $(function() {
        grid.init();

    })
</script>
