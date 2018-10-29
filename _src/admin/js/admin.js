/***********************************************************************************
 * 관리자 페이지 초기화
 ***********************************************************************************/
APP.init = function(){
    APP.initAjaxDefaultSetting();
    APP.initMenu();
    APP.initAx5();
    APP.initPlugins();
    APP.initCheckboxAll();
};

/***********************************************************************************
 * AJAX Error 및 BlockUI 처리
 ***********************************************************************************/
APP.initAjaxDefaultSetting = function() {
    $(document).ajaxError(function(event, request, settings){
        var message = '알수없는 오류가 발생하였습니다.';
        if( typeof request.responseJSON != 'undefined' && typeof request.responseJSON.message != 'undefined' ) {
            message = request.responseJSON.message;
        }
        else {
            if( request.status == 500 ) message = '서버 코드 오류가 발생하였습니다.\n관리자에게 문의하세요';
            else if ( request.status == 401 ) message = '해당 명령을 실행할 권한이 없습니다.';
        }
        toastr.error(message, '오류 발생');
    }).ajaxStart(function(){
        $.blockUI({
            css: {width:'25px',top:'49%',left:'49%',border:'0px none',backgroundColor:'transparent',cursor:'wait'},
            message : '<img src="/assets/images/common/ajax-loader.gif" alt="로딩중">',
            baseZ : 10000,
            overlayCSS : {opacity : 0}
        });
    }).ajaxComplete(function(){
        $.unblockUI();
    });
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
        yearSuffix: '',
        afterShow: function() {
            $('.ui-datepicker-prev').empty().append( '<i class="far fa-chevron-left"></i>' );
            $('.ui-datepicker-next').empty().append( '<i class="far fa-chevron-right"></i>' );
        }
    };

    $.datepicker.setDefaults($.datepicker.regional['ko']);

    $('[data-toggle="datepicker"]').datepicker();

    $("body").on("click", '[data-toggle="datepicker"]', function(){
        if (!$(this).hasClass("hasDatepicker"))
        {
            $(this).datepicker();
            $(this).datepicker("show");
        }
    });

    /*
    $('[data-toggle="formatter"]').each(function(){
        if( $(this).data('pattern') )
        {
            $(this).formatter({
                pattern : $(this).data('pattern'),
                persistent: true
            });
        }
    });

    $.datetimepicker.setLocale('kr');
    $('[data-toggle="datetimepicker"]').datetimepicker({
        format:'Y-m-d H:i'
    });
    */
};

APP.initCheckboxAll = function(){
    $('[data-checkbox]').click(function(){
        var $check = $(this);
        var is_all = ($check.data('checkbox-all') && $check.data('checkbox-all').toString() == 'true');
        var name = $check.data('checkbox');
        var checked = $check.prop('checked');
        var $allCheck = is_all ? $check : $('[data-checkbox="'+name+'"][data-checkbox-all="true"]');

        if( is_all ) {
            $('[data-checkbox="'+name+'"]').prop('checked', checked );
        }
        else {
            $allCheck.prop('checked', $('[data-checkbox="'+name+'"]').not('[data-checkbox-all="true"]').length ==  $('[data-checkbox="'+name+'"]:checked').not('[data-checkbox-all="true"]').length);
        }
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
    APP.toast = new ax5.ui.toast({
        containerPosition: "top-right",
        closeIcon: '<i class="far fa-times"></i>'
    });
};


$(function(){
    APP.init();
});