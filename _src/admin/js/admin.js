/***********************************************************************************
 * 관리자 페이지 초기화
 ***********************************************************************************/
APP.init = function(){
    APP.initMenu();
    APP.initAx5();
    APP.initPlugins();
    APP.initFitHeight();

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
                mH -= $(this).height();
            });

            $('[data-fit-content]').height(mH);
        }
    }).resize();
};


$(function(){
    APP.init();
});