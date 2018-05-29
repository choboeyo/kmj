APP.init = function(){
    APP.initPage();
    APP.initMenu();
    APP.initMask();
    APP.initModal();
    APP.initPlugins();
    APP.initCheckboxAll();
};

APP.initPage = function(){
    if( $('body').height() < $(window).height() )
    {
        $('html,body').css('height', '100%');
    }
};

APP.initMenu = function(){
    $('.btn-menu-toggle').click(function(e){
        $('#left-panel').toggleClass('opened');
        $('#left-panel').niceScroll().resize();
    });


    $('#main .main').niceScroll({
        cursorborder : "1px solid rgba(0,0,0, 0.15)",
        cursorwidth : '12px',
        cursorcolor : 'rgba(0,0,0, 0.5)'
    });

    $('#left-panel').niceScroll({
        cursorborder : "1px solid rgba(0,0,0, 0.15)",
        cursorwidth : '12px',
        cursorcolor : 'rgba(0,0,0, 0.5)'
    });

    $("#left-panel li").each(function(){
        if( $(this).data('active') && $(this).data('active') == menuActive)
        {
            $(this).addClass('active');
            $(this).parents('li').addClass('open');
            $(this).parents('ul').show();
        }
    });

    $('#left-panel #main-navigation a.parent').click(function(e){
        e.preventDefault();
        $(this).parent().toggleClass('open');
        $('#left-panel').niceScroll().resize();
    });
};

APP.initPlugins = function() {

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
        yearSuffix: ''
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

    $('[data-toggle="formatter"]').each(function(){
        if( $(this).data('pattern') )
        {
            $(this).formatter({
                pattern : $(this).data('pattern'),
                persistent: true
            });
        }
    });
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
APP.MASK = null;
APP.MASK2 = null;
APP.modal = null;
APP.modal2 = null;
APP.initMask = function(){
    APP.MASK = new ax5.ui.mask({
        zIndex: 1000
    });
    APP.MASK2 = new ax5.ui.mask({
        zIndex: 2000
    });
};
APP.initModal = function() {
    APP.modal = new ax5.ui.modal({
        absolute: true,
        iframeLoadingMsg: '<i class="far fa-spinner"></i>'
    });
    APP.modal2 = new ax5.ui.modal({
        absolute: true,
        iframeLoadingMsg: '<i class="far fa-spinner"></i>'
    });
};
APP.MODAL = function() {
    var modalCallback = {};

    var defaultCss = {
        width: 400,
        height: 400,
        position: {
            left: "center",
            top: "middle"
        }
    };

    var defaultOption = $.extend(true, {}, defaultCss, {
        iframeLoadingMsg: "",
        iframe: {
            method: "get",
            url: "#"
        },
        closeToEsc: true,
        onStateChanged: function onStateChanged() {
            // mask
            if (this.state === "open") {
                APP.MASK.open();
            } else if (this.state === "close") {
                APP.MASK.close();
            }
        },
        animateTime: 100,
        zIndex: 1001,
        absolute: true,
        fullScreen: false,
        header: {
            title: "새로운 윈도우",
            btns: {
                close: {
                    label: '<i class="far fa-times"></i>', onClick: function onClick() {
                        APP.MODAL.callback();
                    }
                }
            }
        }
    });

    var open = function(modalConfig) {

        modalConfig = $.extend(true, {}, defaultOption, modalConfig);
        $(document.body).addClass("modalOpened");

        this.modalCallback = modalConfig.callback;
        this.modalSendData = modalConfig.sendData;

        APP.modal.open(modalConfig);
    };

    var css = function css(modalCss) {
        modalCss = $.extend(true, {}, defaultCss, modalCss);
        APP.modal.css(modalCss);
    };
    var align = function align(modalAlign) {
        APP.modal.align(modalAlign);
    };
    var close = function close(data) {
        APP.modal.close();
        setTimeout(function () {
            $(document.body).removeClass("modalOpened");
        }, 500);
    };
    var minimize = function minimize() {
        APP.modal.minimize();
    };
    var maximize = function maximize() {
        APP.modal.maximize();
    };
    var callback = function callback(data) {
        if (this.modalCallback) {
            this.modalCallback(data);
        }
        this.close(data);
    };
    var getData = function getData() {
        if (this.modalSendData) {
            return this.modalSendData();
        }
    };

    return {
        "open": open,
        "css": css,
        "align": align,
        "close": close,
        "minimize": minimize,
        "maximize": maximize,
        "callback": callback,
        "modalCallback": modalCallback,
        "getData": getData
    };
}();
APP.MODAL2 = function() {
    var modalCallback = {};

    var defaultCss = {
        width: 400,
        height: 400,
        position: {
            left: "center",
            top: "middle"
        }
    };

    var defaultOption = $.extend(true, {}, defaultCss, {
        iframeLoadingMsg: "",
        iframe: {
            method: "get",
            url: "#"
        },
        closeToEsc: true,
        onStateChanged: function onStateChanged() {
            // mask
            if (this.state === "open") {
                APP.MASK2.open();
            } else if (this.state === "close") {
                APP.MASK2.close();
            }
        },
        animateTime: 100,
        zIndex: 2001,
        absolute: true,
        fullScreen: false,
        header: {
            title: "새로운 윈도우",
            btns: {
                close: {
                    label: '<i class="far fa-times"></i>', onClick: function onClick() {
                        APP.MODAL2.callback();
                    }
                }
            }
        }
    });

    var open = function(modalConfig) {

        modalConfig = $.extend(true, {}, defaultOption, modalConfig);
        $(document.body).addClass("modalOpened");

        this.modalCallback = modalConfig.callback;
        this.modalSendData = modalConfig.sendData;

        APP.modal2.open(modalConfig);
    };

    var css = function css(modalCss) {
        modalCss = $.extend(true, {}, defaultCss, modalCss);
        APP.modal2.css(modalCss);
    };
    var align = function align(modalAlign) {
        APP.modal2.align(modalAlign);
    };
    var close = function close(data) {
        APP.modal2.close();
        setTimeout(function () {
            $(document.body).removeClass("modalOpened");
        }, 500);
    };
    var minimize = function minimize() {
        APP.modal2.minimize();
    };
    var maximize = function maximize() {
        APP.modal2.maximize();
    };
    var callback = function callback(data) {
        if (this.modalCallback) {
            this.modalCallback(data);
        }
        this.close(data);
    };
    var getData = function getData() {
        if (this.modalSendData) {
            return this.modalSendData();
        }
    };

    return {
        "open": open,
        "css": css,
        "align": align,
        "close": close,
        "minimize": minimize,
        "maximize": maximize,
        "callback": callback,
        "modalCallback": modalCallback,
        "getData": getData
    };
}();

$(function(){
    APP.init();
});