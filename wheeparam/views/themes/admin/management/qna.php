<div class="page-header" data-fit-aside>
    <h1 class="page-title">Q&amp;A 관리</h1>
</div>

<form data-grid-search onsubmit="grid.refresh(1);return false;" data-fit-aside autocomplete="off">
    <div data-ax-tbl>
        <div data-ax-tr>
            <div data-ax-td>
                <div data-ax-td-label>작성 기간 검색</div>
                <div data-ax-td-wrap>
                    <input class="form-control" data-chained-datepicker="[name='enddate']" name="startdate" data-toggle="datepicker" value="">
                </div>
                <div data-ax-td-wrap>
                    <input class="form-control" name="enddate" data-toggle="datepicker" value="">
                </div>
            </div>
            <div data-ax-td>
                <div data-ax-td-label>답변여부</div>
                <div data-ax-td-wrap>
                    <select class="form-control" name="qna_ans_status">
                        <option value="">전체보기</option>
                        <option value="N">미답변</option>
                        <option value="Y">답변완료</option>
                    </select>
                </div>
            </div>
            <div data-ax-td>
                <div data-ax-td-label>검색어 입력</div>
                <div data-ax-td-wrap>
                    <select class="form-control form-control-inline" name="sc">
                        <option value="qna_title">질문 제목</option>
                        <option value="qna_name">작성자</option>
                        <option value="qna_phone">연락처</option>
                        <option value="qna_email">이메일</option>
                    </select>
                </div>
            </div>

            <div data-ax-td>
                <div data-ax-td-wrap>
                    <input class="form-control" name="st" value="">
                </div>
                <div data-ax-td-wrap>
                    <button class="btn btn-default btn-sm"><i class="fal fa-search"></i> 필터적용</button>
                </div>
            </div>

            <div data-ax-td class="right">
                <div data-ax-td-wrap>
                    <button type="button" class="btn btn-default btn-sm" data-button="qna-category"><i class="fal fa-sitemap"></i> Q&amp;A 유형 관리</button>
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
            {caption:'질문일시', dataField:'reg_datetime', alignment:'center', width:120},
            {caption:'Q&A유형', dataField:'qnc_title', alignment:'center', width:120},
            {caption:'질문제목', dataField:'qna_title', alignment:'left'},
            {caption:'질문자', dataField:'qna_name', alignment:'center', width:80},
            {caption:'구분', dataField:'reg_user', alignment:'center', width:80, customizeText:function(cell) {return cell.value > 0 ?'회원':'비회원'}},
            {caption:'연락처', dataField:'qna_phone', alignment:'center', width:120},
            {caption:'E-mail', dataField:'qna_email', alignment:'left', width:180},
            {caption:'답변여부', dataField:'qna_ans_status', alignment:'center', width:60, customizeText:function(cell) {return cell.value == 'Y' ?'답변완료':''}},
            {caption:'답변자', dataField:'qna_ans_upd_username', alignment:'center', width:80},
            {caption:'답변일시', dataField:'qna_ans_upd_datetime', alignment:'center', width:120, customizeText:function(cell) {return cell.value != '0000-00-00 00:00:00' ?cell.value:''}}
        ],
        dataSource: new DevExpress.data.DataSource({
            key : 'qna_idx',
            load: function(loadOptions) {
                var d = $.Deferred();
                var params = grid.getSearchParam(loadOptions);

                $.ajax({
                    url : base_url + '/admin/ajax/management/qna',
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
                        icon : 'search',
                        text: '내용보기',
                        onItemClick: function () {
                            grid.form(e.row.data.qna_idx);
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
        onRowDblClick: function(e) {
            grid.form(e.data.qna_idx);
        },
    });

    grid.form = function(qna_idx) {
        APP.MODAL.callback = function() {
            grid.refresh();
            APP.MODAL.close();
        }

        APP.MODAL.open({
            iframe: {
                url: base_url + '/admin/management/qna_view/' + qna_idx
            },
            width:800,
            height:600,
            header: {
                title: 'Q&A 내용보기'
            }
        })
    };


    grid.delete = function(data) {
        if(! confirm('선택하신 데이타를 삭제하시겠습니까?\n제목: '+data.qna_title + '\n작성자: ' + data.qna_name)) return false;

        $.ajax({
            url: base_url + '/admin/ajax/management/qna',
            type: 'DELETE',
            data: {
                qna_idx: data.qna_idx
            },
            success:function() {
                toastr.success('삭제되었습니다.');
                grid.refresh();
            }
        })
    }

    $(function() {
        grid.init();

        $('[data-button="qna-category"]').click(function() {
            APP.MODAL.open({
                iframe: {
                    url: base_url + '/admin/management/qna_category'
                },
                width: 800,
                height: 600,
                header: {
                    title: 'Q&A 유형 관리'
                }
            })

        });
    })
</script>