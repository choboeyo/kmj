<div class="page-header" data-fit-aside>
    <h1 class="page-title">게시판 관리</h1>
</div>

<div class="ax-button-group" data-fit-aside>
    <button type="button" class="btn btn-primary" onclick="grid.form();"><i class="fal fa-plus"></i> 게시판 신규등록</button>
</div>

<div class="grid-wrapper" data-fit-content>
    <div id="grid-container" class="grid-container"></div>
</div>

<script>
    var grid = new GRID('#grid-container', {
        columns: [
            {caption:'고유KEY', dataField:'brd_key', width:80, alignment:'left'},
            {caption:'게시판이름', dataField:'brd_title', minWidth:100, alignment:'left'},
            {
                caption:'스킨',
                columns: [
                    {caption:'목록', dataField:'brd_skin_l', width:80, alignment:'left'},
                    {caption:'목록(M)', dataField:'brd_skin_l_m', width:80, alignment:'left'},
                    {caption:'글쓰기', dataField:'brd_skin_w', width:80, alignment:'left'},
                    {caption:'글쓰기(M)', dataField:'brd_skin_w_m', width:80, alignment:'left'},
                    {caption:'글보기', dataField:'brd_skin_v', width:80, alignment:'left'},
                    {caption:'글보기(M)', dataField:'brd_skin_v_m', width:80, alignment:'left'},
                    {caption:'댓글', dataField:'brd_skin_c', width:80, alignment:'left'},
                    {caption:'댓글 (M)', dataField:'brd_skin_c_m', width:80, alignment:'left'},
                ]
            },
            {caption:'목록개수', dataField:'brd_page_rows', width:60, alignment:'right', dataType:'number', format:'fixedPoint'},
            {caption:'현재글수', dataField:'brd_count_post', width:60, alignment:'right', dataType:'number', format:'fixedPoint'},
            {
                caption:'기능사용',
                columns: [
                    {caption:'카테고리', dataField:'brd_use_category', alignment:'center', width:60, customizeText:function(cell){return cell.value == 'Y'?'사용':''}},
                    {caption:'답글기능', dataField:'brd_use_reply', alignment:'center', width:60, customizeText:function(cell){return cell.value == 'Y'?'사용':''}},
                    {caption:'댓글기능', dataField:'brd_use_comment', alignment:'center', width:60, customizeText:function(cell){return cell.value == 'Y'?'사용':''}},
                    {caption:'익명', dataField:'brd_use_category', alignment:'center', width:60, customizeText:function(cell){return cell.value == 'Y'?'사용':(cell.value == 'A'?'강제사용':'')}},
                    {caption:'비밀글', dataField:'brd_use_category', alignment:'center', width:60, customizeText:function(cell){return cell.value == 'Y'?'사용':(cell.value == 'A'?'강제사용':'')}},
                    {caption:'첨부파일', dataField:'brd_use_attach', alignment:'center', width:60, customizeText:function(cell){return cell.value == 'Y'?'사용':''}},
                    {caption:'이름가리기', dataField:'brd_blind_nickname', alignment:'center', width:75, customizeText:function(cell){return cell.value == 'Y'?'사용':''}},
                ]
            },
            {
                caption:'권한레벨',
                columns: [
                    {caption:'목록', dataField:'brd_lv_list', width:60, alignment:'right', dataType:'number', format:'fixedPoint'},
                    {caption:'글쓰기', dataField:'brd_lv_write', width:60, alignment:'right', dataType:'number', format:'fixedPoint'},
                    {caption:'글보기', dataField:'brd_lv_read', width:60, alignment:'right', dataType:'number', format:'fixedPoint'},
                    {caption:'답글', dataField:'brd_lv_reply', width:60, alignment:'right', dataType:'number', format:'fixedPoint'},
                    {caption:'댓글', dataField:'brd_lv_comment', width:60, alignment:'right', dataType:'number', format:'fixedPoint'},
                    {caption:'다운로드', dataField:'brd_lv_download', width:60, alignment:'right', dataType:'number', format:'fixedPoint'},
                ]
            },
        ],
        dataSource: new DevExpress.data.DataSource({
            key : 'brd_key',
            load: function(loadOptions) {
                var d = $.Deferred();
                var params = grid.getSearchParam(loadOptions);

                $.ajax({
                    url : base_url + '/admin/ajax/board',
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
            grid.form(e.data.brd_key);
        },
        onContextMenuPreparing: function(e) {
            if (e.row.rowType === "data") {
                e.items = [
                    {
                        icon: 'edit',
                        text: '정보 수정',
                        onItemClick: function () {
                            grid.form(e.row.data.brd_key);
                        }
                    },
                    {
                        icon: 'edit',
                        text: '게시판복사',
                        onItemClick: function () {
                            grid.copy_board(e.row.data.brd_key);
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
    
    grid.form = function(brd_key) {
        brd_key = typeof brd_key != 'undefined' && brd_key ? brd_key : '';
        
        APP.MODAL.callback = function() {
            APP.MODAL.close();
            grid.refresh();
        }
        APP.MODAL.open({
            iframe: {
                url :base_url + '/admin/board/form/' + brd_key
            },
            width: 940,
            height: 600,
            header: {
                title: '게시판 정보 입력'
            }
        })
    };

    grid.copy_board = function(brd_key) {
        APP.MODAL.callback = function() {
            APP.MODAL.close();
            grid.refresh();
        };
        APP.MODAL.open({
            iframe : {
                url : base_url + '/admin/board/board_copy/'+brd_key,
                param : {
                    brd_key : brd_key
                }
            },
            header : {
                title : '게시판 복사하기'
            },
            width:400,
            height:300
        });
    };
    
    $(function() {
       grid.init();
    });
</script>