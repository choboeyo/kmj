<div class="W600 margin-auto">
<div class="page-header" data-fit-aside>
    <h1 class="page-title">관리자 관리</h1>
</div>

<div class="ax-button-group" data-fit-aside>
    <div class="left">
        <button type="button" class="btn btn-primary" onclick="grid.form()"><i class="fal fa-plus"></i> 관리자 추가</button>
    </div>
</div>

<div class="grid-wrapper" data-fit-content>
    <div class="grid-container" id="grid-container"></div>
</div>
</div>
<script>
    var grid = new GRID('#grid-container', {
        columns: [
            {caption:'순번', dataField:'nums', alignment:'right', dataType:'number', format:'fixedPoint', width:80},
            {caption:'아이디', dataField:'mem_userid', width:200},
            {caption:'이름', dataField:'mem_nickname', alignment:'center', width:80},
            {caption:'가입일시', dataField:'mem_regtime', alignment:'center', width:120},
            {caption:'가입IP', dataField:'regip', alignment:'center', width:120},
            {caption:'최근로그인', dataField:'mem_logtime', alignment:'center', width:120},
            {caption:'최근로그인IP', dataField:'logip', alignment:'center', width:120},
        ],
        dataSource: new DevExpress.data.DataSource({
            key : 'mem_idx',
            load: function(loadOptions) {
                var d = $.Deferred();
                var params = grid.getSearchParam(loadOptions);

                $.ajax({
                    url : base_url + '/admin/ajax/setting/admins',
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
                    }
                ]
            }
        },
    });

    grid.form = function() {
        APP.MODAL.callback = function() {
            APP.MODAL.close();
            grid.refresh();
        };
        APP.MODAL.open({
            iframe : {
                url : base_url + '/admin/setting/admin_add'
            },
            header : {
                title : '관리자 추가'
            },
            width: 800,
            height:600
        })
    };

    grid.delete = function(data) {
        if( ! confirm('[' + data.mem_nickname + '] 님의 관리자 권한을 제거하고 권한레벨을 초기값으로 설정하시겠습니까?') ) return false;
        $.ajax({
            url : '/admin/ajax/setting/admins',
            type : 'DELETE',
            data : {
                mem_idx : data.mem_idx
            },
            success:function(res) {
                toastr.success('['+data.mem_nickname+'] 님의 관리자 권한을 제거하였습니다.');
                grid.refresh();
            }
        })
    };

    $(function() {
        grid.init();
    })

</script>