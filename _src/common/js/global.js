/***********************************************************************************
 * IE 8 이하 브라우져 console.log 에러처리
 ***********************************************************************************/
if(!window.console || !window.console.log) {window.console = {log : function(){}};}


/***********************************************************************************
 * AJAX Error 및 BlockUI 처리
 ***********************************************************************************/
$(function(){
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
});
var APP = {};
APP.POPUP = null;
APP.REGEX = {};
APP.REGEX.uniqueID = /^[a-z][a-z0-9_]{2,19}$/g;
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

/**
 * 언어셋 변경
 * @param lang
 * @constructor
 */
APP.SET_LANG = function(lang)
{
    $.cookie('site_lang', lang, {expires:30, path:'/'});
    location.reload();
};

/**
 * 팝업창 닫기버튼 init
 */
$('[data-toggle="btn-popup-close"]').click(function(e){
    var type = $(this).data('type');
    var idx = $(this).data('idx');
    var cookie = $(this).data('cookie');

    if( type == 'Y')
    {
        window.close();
    }
    else if( type == 'N' )
    {
        $("#popup-" + idx ).remove();
    }

    if( cookie == 1 )
    {
        $.cookie('popup_'+idx, 1, {expires:1, path:'/'});
    }
});

/**
 * SNS 공유
 */
$("a[data-toggle='sns-share']").not('[data-service="link"]').click(function(e){
    e.preventDefault();

    var _this = $(this);
    var sns_type = _this.data('service');
    var href = _this.data('url');
    var title = _this.data('title');
    var loc = "";
    var img = $("meta[name='og:image']").attr('content');

    if( ! sns_type || !href || !title) return;

    if( sns_type == 'facebook' ) {
        loc = '//www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(href);
    }
    else if ( sns_type == 'twitter' ) {
        loc = '//twitter.com/home?status='+encodeURIComponent(title)+' '+href;
    }
    else if ( sns_type == 'google' ) {
        loc = '//plus.google.com/share?url='+href;
    }
    else if ( sns_type == 'pinterest' ) {

        loc = '//www.pinterest.com/pin/create/button/?url='+href+'&media='+img+'&description='+encodeURIComponent(title);
    }
    else if ( sns_type == 'kakaostory') {
        loc = 'https://story.kakao.com/share?url='+encodeURIComponent(href);
    }
    else if ( sns_type == 'band' ) {
        loc = 'http://www.band.us/plugin/share?body='+encodeURIComponent(title)+'%0A'+encodeURIComponent(href);
    }
    else if ( sns_type == 'naver' ) {
        loc = "http://share.naver.com/web/shareView.nhn?url="+encodeURIComponent(href)+"&title="+encodeURIComponent(title);
    }
    else if ( sns_type == 'line') {
        loc = "http://line.me/R/msg/text/?" + encodeURIComponent(title + "\n" + href);
    }
    else {
        return false;
    }
    APP.POPUP({ url : loc});
    return false;
});

$(function(){
    var clipboard = new ClipboardJS('a[data-toggle="sns-share"][data-service="link"]', {
        text: function(trigger) {
            return trigger.getAttribute('data-url');
        }
    });
    clipboard.on('success', function(){
        alert('현재 URL이 복사되었습니다.');
    });
});