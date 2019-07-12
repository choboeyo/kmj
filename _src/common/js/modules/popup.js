APP.POPUP = null;
(function($) {
    APP.POPUP = function(option) {
        var defaults={
            title : '_blank',
            width : 800,
            height : 600,
            url : ''
        };

        var options = $.extend({}, defaults, option);

        cw = screen.availWidth;
        ch = screen.availHeight;
        sw = options.width;
        sh = options.height;

        ml = (cw - sw) / 2;
        mt = (ch - sh) / 2;
        var option = 'width='+sw+',height='+sh+',top='+mt+',left='+ml+',scrollbars=yes,resizable=no';
        var win = window.open(options.url, options.title,  option);
        if (win == null || typeof(win) == "undefined" || (win == null && win.outerWidth == 0) || (win != null && win.outerHeight == 0))
        {
            alert("팝업 차단 기능이 설정되어있습니다\n\n차단 기능을 해제(팝업허용) 한 후 다시 이용해 주십시오.");
            return;
        }
    };
})(jQuery);