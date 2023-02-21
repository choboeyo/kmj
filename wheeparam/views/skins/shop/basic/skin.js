$(document).ready(function() {

    /*
     * --------------------------------------------------------------------
     * 상품 상세보기 페이지에서 썸네일 이미지 마우스 오버시, 큰 이미지 교체
     * --------------------------------------------------------------------
     */
    if( $().length> 0
        && $('[data-container="product-small-images"]').length > 0 )
    {
        // data-container="product-big-images" 와 data-container="product-small-images" 가 존재하는경우만 실행

        // 마우스 엔터 이벤트
        $('[data-container="product-small-images"] li .thumbnail')
            .off('mouseenter.thumbnail_hover')
            .on('mouseenter.thumbnail_hover',function() {
                // 마우스 오버된 썸네일 이미지의 index를 구해온다.
                var index = $(this).parents('li').index();

                // 큰 이미지의 해당 인덱스만 visible 클래스를 주고 나머지는 visible 클래스를 빼준다.
                $('[data-container="product-big-images"] li')
                    .eq(index).addClass('visible')
                    .siblings().removeClass('visible');

                // 마우스 오버된 썸네일 이미지의 visible 클래스를 주고 나머지는 visible 클래스를 빼준다.
                $(this).parents('li').addClass('visible')
                    .siblings().removeClass('visible');

                // 이미지 크게 보기 버튼의 링크를 바꿔준다.
                $('[data-button="product-image-zoom"]').attr('href', $('[data-container="product-big-images"] li').eq(index).find('.thumbnail img').attr('src') )
            });
    }

    /*
     * --------------------------------------------------------------------
     * 상품 이미지 확대하기
     * --------------------------------------------------------------------
     */
    if($('[data-button="product-image-zoom"]').length > 0) {
        $('[data-button="product-image-zoom"]').magnificPopup({
            type: 'image',
            closeOnContentClick: true,
            image: {
                verticalFit: false
            }
        });

    }
});