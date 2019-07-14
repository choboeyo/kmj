/***********************************************************************************
 * 관리자 페이지 초기화
 ***********************************************************************************/
APP.init = function(){
    APP.initMenu();
    APP.initAx5();
    APP.initPlugins();
    APP.initFitHeight();
    APP.initSortableList();

    DevExpress.localization.locale('ko');
};

/***********************************************************************************
 * 메뉴관련 초기화
 ***********************************************************************************/
APP.initMenu = function(){
    $('#nav .main-navigation li').each(function(){
       var $this = $(this);
       var menuCode = $this.data('active');

       if(menuCode == menuActive)
       {
           $(this).addClass('active');
           $(this).parents('li').addClass('active');
       }
    });
};

APP.initPlugins = function() {
    $.datepicker._updateDatepicker_original = $.datepicker._updateDatepicker;
    $.datepicker._updateDatepicker = function(inst) {
        $.datepicker._updateDatepicker_original(inst);
        var afterShow = this._get(inst, 'afterShow');
        if (afterShow)
            afterShow.apply((inst.input ? inst.input[0] : null));
    }
    $.datepicker.regional['ko'] = {
        closeText: '닫기',
        prevText: '이전달',
        nextText: '다음달',
        currentText: '오늘',
        monthNames: ['1월','2월','3월','4월','5월','6월', '7월','8월','9월','10월','11월','12월'],
        monthNamesShort: ['1월','2월','3월','4월','5월','6월', '7월','8월','9월','10월','11월','12월'],
        dayNames: ['일','월','화','수','목','금','토'],
        dayNamesShort: ['일','월','화','수','목','금','토'],
        dayNamesMin: ['일','월','화','수','목','금','토'],
        weekHeader: 'Wk',
        dateFormat: 'yy-mm-dd',
        firstDay: 0,
        isRTL: false,
        showMonthAfterYear: true,
        changeMonth: true,
        changeYear: true,
        yearSuffix: ''
    };

    $.datepicker.setDefaults($.datepicker.regional['ko']);

    $('[data-toggle="datepicker"]').each(function(){
       $(this).datepicker();
       if( typeof $(this).data('chained-datepicker') != 'undefined' && $(this).data('chained-datepicker') )
       {
           var el = $(this).data('chained-datepicker'),
               $el = $(el);

           if($el.length > 0 ) {
               $(this).change(function() {
                   if($el.hasClass('hasDatepicker')) {
                       $el.datepicker('option', 'minDate', $(this).val() );
                   }
               })
           }
       }
    });

    $("body").on("click", '[data-toggle="datepicker"]', function(){
        if (!$(this).hasClass("hasDatepicker"))
        {
            $(this).datepicker();
            $(this).datepicker("show");
        }
    });
};

APP.initSortableList = function() {

    $('[data-toggle="sortable"]').each(function(){

        if( $(this).hasClass('has-sortable') ) return true;

        $(this).addClass('has-sortable');

        var $this = $(this);
        var key = $(this).data('key');
        var table = $(this).data('table');
        var sort = $(this).data('sort');

        $this.sortable({
            handle : '.move-grip',
            update : function() {
                var sortArray = [];
                $('input[name="'+key+'[]"]').each(function(){
                    sortArray.push( $(this).val() );
                });
                $.ajax({
                    url : base_url + '/admin/ajax/management/sort',
                    type : 'POST',
                    data : {
                        key : key,
                        table : table,
                        sort : sort,
                        sort_order : sortArray
                    },
                    success:function(res) {
                        toastr.success('순서변경이 적용되었습니다.');
                    }
                })
            }
        })
    });
};

/**********************************************************************************************************************
 * MODAL 관련
 *********************************************************************************************************************/
APP.initAx5 = function(){
    APP.MASK = new ax5.ui.mask({
        zIndex: 1000}
        );
    APP.MASK2 = new ax5.ui.mask({
        zIndex: 2000
    });
    APP.modal = new ax5.ui.modal({
        absolute: true,
        iframeLoadingMsg: '<i class="far fa-spinner"></i>'
    });
    APP.modal2 = new ax5.ui.modal({
        absolute: true,
        iframeLoadingMsg: '<i class="far fa-spinner"></i>'
    });
};

APP.initFitHeight = function() {
    $(window).resize(function() {
        if($('[data-fit-content]').length> 0 )
        {
            var mH = $('#contents').height();

            $('[data-fit-aside]').each(function() {
                mH -= $(this).outerHeight(true);
            });

            $('[data-fit-content]').height(mH);
        }
    }).resize();
};

APP.memberMenuObject = function(e, point_name, obj) {
    var a = [
        {
            icon: 'card',
            text: '회원정보',
            beginGroup:true,
            onItemClick: function() {
                APP.MEMBER.POP_INFO_ADMIN(e.row.data.mem_idx);
            }
        },
        {
            icon: 'edit',
            text: '정보수정',
            onItemClick: function() {
                APP.MEMBER.POP_MODIFY_ADMIN(e.row.data.mem_idx);
            }
        },
        {
            icon: 'key',
            text: '비밀번호 변경',
            onItemClick: function() {
                APP.MEMBER.POP_PASSWORD_ADMIN(e.row.data.mem_idx);
            }
        },
        {
            beginGroup:true,
            icon:'repeat',
            text: '휴면처리',
            visible: e.row.data.mem_status == 'Y',
            onItemClick: function() {
                APP.MEMBER.STATUS_CHANGE(e.row.data.mem_idx,'Y','H');
            }
        },
        {
            icon:'clear',
            text: '로그인금지',
            visible: e.row.data.mem_status == 'Y',
            onItemClick: function() {
                APP.MEMBER.STATUS_CHANGE(e.row.data.mem_idx,'Y','D');
            }
        },
        {
            icon:'clearformat',
            text: '휴면해제',
            visible: e.row.data.mem_status == 'H',
            onItemClick: function() {
                APP.MEMBER.STATUS_CHANGE(e.row.data.mem_idx,'H','Y');
            }
        },
        {
            icon:'clearformat',
            text: '로그인금지 해제',
            visible: e.row.data.mem_status == 'D',
            onItemClick: function() {
                APP.MEMBER.STATUS_CHANGE(e.row.data.mem_idx,'D','Y');
            }
        },
        {
            icon:'trash',
            text: '회원 탈퇴',
            visible: e.row.data.mem_status != 'N',
            onItemClick: function() {
                APP.MEMBER.STATUS_CHANGE(e.row.data.mem_idx,'D','Y');
            }
        },
        {
            icon:'event',
            beginGroup:true,
            text: '로그인 기록',
            onItemClick: function() {
                APP.POPUP({
                    url: base_url + '/admin/members/log?mode=popup&sc=idx&st=' + e.row.data.mem_idx
                })
            }
        },
        {
            icon:'unpin',
            beginGroup:true,
            text: point_name + ' 관리',
            visible: point_name !== false,
            onItemClick: function() {
                APP.MEMBER.POP_POINT_ADMIN(e.row.data.mem_idx);
            }
        },
        {
            icon:'unpin',
            text: point_name + ' 추가',
            visible: point_name !== false,
            onItemClick: function() {
                APP.MEMBER.POP_POINT_FORM_ADMIN(e.row.data.mem_idx);
            }
        }
    ]

    return a;
};

$(function(){
    APP.init();
});