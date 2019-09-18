/**
 * GRID Element 생성자
 * @param element
 * @param options
 * @constructor
 */
function GRID (element, addOptions) {
    this.element = element;
    var self = this;
    this.defaultOptions = {
        key : null,
        cacheEnabled: false,
        statusField : {
            key : '',
            values : []
        },
        sorting: {
            mode:'none',
            ascendingText: '오름차순 정렬',
            clearText: '정렬 초기화',
            descendingText: '내림차순 정렬'
        },
        colorField : [],
        loadPanel: { enabled: true },
        remoteOperations: {
            groupPaging: true,
            sorting:true,
            paging:true,
            summary:false
        },
        paging: {
            pageSize: 30,
            showNavigationButtons: true
        },pager: {
            showNavigationButtons: true,
            showPageSizeSelector: true,
            allowedPageSizes: [10, 15, 20, 30, 50, 100],
            infoText: "{0}페이지 / 총 {1} 페이지 (검색결과 : {2}건)",
            showInfo: true,
            visible: true
        },
        selection : {
            mode: 'single',
            selectAllMode : 'page',
            showCheckBoxesMode: "always"
        },
        scrolling: {
            showScrollbar: 'always'
        },
        focusedRowEnabled: true,
        columns: null,
        dataSource: null,
        showRowLines:true,
        showColumnLines:true,
        allowColumnResizing: true,
        noDataText: '검색된 데이타가 없습니다.',
        allowColumnReordering: true,
        columnResizingMode: 'widget',
        editing: {
            allowUpdating: false,
            allowAdding: false,
            allowDeleting: false
        },
        columnFixing: {
            enabled: true,
            texts: {
                fix: '열 고정',
                leftPosition: '왼쪽에 고정',
                rightPosition: '오른쪽에 고정',
                unfix:'열 고정 해제'
            }
        },
        onRowClick: function(e) {
            self.OnRowSingleClick(e.data);
        },
        onRowDblClick: function(e) {
            self.OnRowDoubleClick(e.data);
        },
        onRowPrepared: function(e) {
            if(e.rowType == 'data') {
                var k = self.options.statusField.key,
                    v = self.options.statusField.values,
                    t = typeof v;
                if( k && ( (t == 'string' && v) || (t == 'object' && v.length > 0)) ) {
                    if( (t == 'string' && e.data[k] == v) || (t == 'object' && $.inArray( e.data[k], v) > -1) ) {
                        e.rowElement.addClass('unused');
                    }
                }

                self.OnRowPrepared(e);
            }
        },
        onContextMenuPreparing: function(e) {
            if(e.rowType == 'data') self.OnContextMenuPreparing(e);
        },
        onSelectionChanged:function(e) {
            self.OnSelectionChanged(e);
        },
        onCellPrepared: function(e) {
            if(e.rowType == 'data') {
                if(typeof self.options.colorField == 'object' && self.options.colorField.length > 0)
                {
                    for(var i in self.options.colorField) {
                        if( self.options.colorField[i].key && self.options.colorField[i].color && e.column.dataField == self.options.colorField[i].key ) {
                            e.cellElement.css("background-color", self.options.colorField[i].color);
                        }
                    }
                }
                self.OnCellPrepared(e);
            }
        }
    };
    this.options = $.extend({}, this.defaultOptions, addOptions);
}

GRID.prototype.instance = function() {
    return $(this.element).dxDataGrid('instance');
}
/**
 * 그리드를 초기화한다.
 */
GRID.prototype.init = function() {
    // 그리드 초기화
    $(this.element).dxDataGrid(this.options);
    var that = this;
    if( $('[data-column]').length > 0 ) {
        $('[data-column]').change(function(e) {
            var field = $(this).data('column'),
                visible = $(this).prop('checked');
            $(that.element).dxDataGrid('columnOption', field, 'visible', visible);
        }).change();
    }
};

GRID.prototype.SetPage = function(pageIndex) {
    $(this.element).dxDataGrid('instance').pageIndex(pageIndex - 1);
};

GRID.prototype.getSearchParam = function(loadOptions) {
    var params = {};

    ["skip","take","requireTotalCount","requireGroupCount","sort","filter","totalSummary","group","groupSummary"].forEach(function(i) {
        if(i in loadOptions && loadOptions[i] !== undefined && loadOptions[i] !== null && loadOptions[i] !== '')
            params[i] = JSON.stringify(loadOptions[i]);
    });

    if( $('[data-grid-search]').length > 0 ) {
        var $form = $('[data-grid-search]');
        $('input', $form).not('[type="checkbox"]').not('[type="radio"]').each(function() {
            var name = $(this).attr('name'),
                value = $(this).val();
            params[name] = value;
        });
        $('input[type="checkbox"]', $form).each(function() {
            if( $(this).prop('checked') ) {
                var name = $(this).attr('name'),
                    value = $(this).val();
                params[name] = value;
            }
        });
        $('input[type="radio"]:checked', $form).each(function() {
            var name = $(this).attr('name'),
                value = $(this).val();
            params[name] = value;
        });
        $('select', $form).each(function() {
            var name = $(this).attr('name'),
                value = $(this).find('option:selected').val();
            params[name] = value;
        })
    }

    return params;
}

/**
 * 그리드를 새로고침한다.
 */
GRID.prototype.refresh = function(pageIndex) {
    pageIndex = typeof pageIndex == 'number' && pageIndex > 0 ? pageIndex : null;
    if(pageIndex) {
        $(this.element).dxDataGrid('instance').pageIndex(pageIndex - 1);
    }
    $(this.element).dxDataGrid('instance').refresh();
};

/**
 * 컬럼을 감추고 숨길수 있다
 * @param field
 * @param visible
 * @constructor
 */
GRID.prototype.SetColumnVisible = function(field, visible) {

};

/**
 * 마우스 한번 클릭시 실행되는 이벤트
 * @param e
 * @constructor
 */
GRID.prototype.OnRowSingleClick = function(e) {};

/**
 * 마우스 더블클릭시 실행되는 이벤트
 * @param e
 * @constructor
 */
GRID.prototype.OnRowDoubleClick = function(e) {};

/**
 * 행이 준비되었을때 실행되는 이벤트
 * @param e
 * @constructor
 */
GRID.prototype.OnRowPrepared = function(e) {};

/**
 * 마우스 우클릭시 나타나는 바로가기 메뉴
 * @param e
 * @constructor
 */
GRID.prototype.OnContextMenuPreparing = function(e) {};

/**
 * 행 선택이 바뀌었을때 실행되는 이벤트
 * @param e
 * @constructor
 */
GRID.prototype.OnSelectionChanged = function(e) {};

/**
 * 셀이 준비되었을때 실행되는 이벤트
 * @param e
 * @constructor
 */
GRID.prototype.OnCellPrepared = function(e) {};