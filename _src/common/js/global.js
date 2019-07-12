/***********************************************************************************
 * IE 8 이하 브라우져 console.log 에러처리
 ***********************************************************************************/
if(!window.console || !window.console.log) {window.console = {log : function(){}};}

var APP = {};

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