// APP 객체가 선언되어 있지 않으면 객체 선언
if(typeof APP === 'undefined' || ! APP) {
    var APP = {};
}

if(typeof $ === 'undefined' || ! $) {
    throw Error('jQuery가 로드되지 않았습니다.');
}

/*
 * ---------------------------------------------------------------
 *  메인 영역을 브라우져 높이에 맞춘다.
 *
 *  - 헤더와 푸터의 위치를 항상 맨위와 맨아래에 올수 있도록,
 *    메인 영역의 크기를 자동으로 처리한다.
 * ---------------------------------------------------------------
 */
$(document).ready(function() {
    // 윈도우 리사이즈 이벤트 정의 및 바로 실행
    $(window).resize(function() {
        // 전체 브라우져 높이에서 [data-fit-aside] 영역의 높이들을 뺀 크기를
        // [data-fit] 영역의 min-height로 집어넣어준다.
        var $fit_content = $('[data-fit]'),
            $fit_asides = $('[data-fit-aside]');

        // [data-fit] 엘리먼트가 없는경우 처리하지 않는다.
        if($fit_content.length === 0) {
            return;
        }

        // 전체 브라우져 높이 구하기
        var h = ($(window).height() * 1)
        // 전체 브라우져 높이에서 [fit-aside] 엘리먼트 숫자만큼 반복문을 돌려 최소영역을 구해준다.
        $fit_asides.each(function() {
            h -= ($(this).outerHeight() * 1);
        })

        if(h > 0) {
            $fit_content.css({
                'min-height' : ( h / $fit_content.length ) + 'px'
            })
        } else {
            $fit_content.css({
                'min-height':'none'
            })
        }

    }).resize();
});

/*
 * ---------------------------------------------------------------
 *  NAVBAR TOGGLE
 *
 *  - [data-button="menu-toggle"] 에 클릭이벤트를 추가하여
 *  클릭시마다 body 에 menu-opened 클래스를 추가/제거 한다.
 * ---------------------------------------------------------------
 */
APP.isMenuOpened = false;
$(document).ready(function(){
    $('[data-button="menu-toggle"]')
        .off('click.menu_toggle')
        .on('click.menu_toggle', function(e) {
            e.preventDefault();
            $('body').toggleClass('menu-opened');
            APP.isMenuOpened = $('body').hasClass('menu-opened');
        })
})

/**
 * 버튼 콜백
 */
APP.onMenuClick = function() {

}