<div class="ax-button-group" data-fit-aside>
    <div class="left">
        <h2>사이트맵 설정</h2>
    </div>
</div>

<p class="alert alert-info" data-fit-aside>
    <i class="fal fa-info-circle"></i> 추가로 sitemap.xml 에서 인덱싱하고싶은 URL을 관리하는 페이지입니다.<br>
    <i class="fal fa-info-circle"></i> 메인페이지와 게시판은 자동으로 로드하므로 추가할 필요가 없습니다.
</p>

<div class="ax-button-group" data-fit-aside>
    <div class="left">
        <button type="button" class="btn btn-default" onclick="grid.form()"><i class="fal fa-plus"></i> 추가하기</button>
    </div>
    <div class="right">
        <button type="button" class="btn btn-default" onclick="grid.refresh()"><i class="fal fa-sync"></i> 새로고침</button>
    </div>
</div>

<div class="grid-wrapper" data-fit-content>
    <div class="grid-container" id="grid-container"></div>
</div>

<script>
    var grid = new GRID('#grid-container', {
        editing: {
            mode: "cell",
            allowUpdating: true,
            allowDeleting: false,
            allowAdding: false
        },
        columns: [
            {caption:'URL', dataField:'sit_loc', width:350, customizeText:function(cell) {return base_url.substr(0, base_url.length -1) + cell.value}},
            {caption:'중요도', dataField:'sit_priority', dataFormat:'number', width:100, lookup: {dataSource: [0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9]}},
            {caption:'갱신주기', dataField:'sit_changefreq', width:150, lookup: {dataSource: ['daily','weekly','monthly']}},
            {caption:'메모', dataField:'sit_memo', minWidth:200},
            {caption:'최종수정자', dataField:'upd_username', alignment:'center', width:120, allowEditing:false },
            {caption:'최종수정일', dataField:'upd_datetime', alignment:'center', width:120, allowEditing:false},

        ],
        dataSource: new DevExpress.data.DataSource({
            key : 'sit_idx',
            load: function(loadOptions) {
                var d = $.Deferred();
                var params = grid.getSearchParam(loadOptions);

                $.ajax({
                    url : base_url + '/admin/ajax/management/sitemaps',
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
            },
            update: function(key, values) {
                var d = $.Deferred();

                $.ajax({
                    url : '/admin/ajax/management/updates',
                    type: 'POST',
                    async: false,
                    cache: false,
                    data : {
                        table : 'sitemap',
                        key_column: 'sit_idx',
                        key : key,
                        values : values
                    }
                }).done(function(result) {
                    d.resolve(result);
                    grid.refresh();
                });
                return d.promise();
            },
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
                url : base_url + '/admin/management/sitemap_form'
            },
            header  : {
                title : '사이트맵 등록하기'
            },
            width:600,
            height:200

        });
    };
    grid.delete = function(data) {
        if(! confirm('선택하신 데이타를 삭제하시겠습니까?\nURL: '+data.sit_loc)) return false;

        $.ajax({
            url: base_url + '/admin/ajax/management/sitemaps',
            type: 'DELETE',
            data: {
                sit_idx: data.sit_idx
            },
            success:function() {
                toastr.success('삭제되었습니다.');
                grid.refresh();
            }
        })
    }
    $(function() {
        grid.init();
    })
</script>