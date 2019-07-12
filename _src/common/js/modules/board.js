APP.BOARD = {};
APP.BOARD.CATEGORY = {};
APP.BOARD.EXTRA = {};
APP.BOARD.COMMENT = {};

/**
 * 특정 카테고리의 하위 카테고리가 몇개인지 가져온다.
 * @param bca_idx
 */
APP.BOARD.CATEGORY.count = function(bca_idx)
{
    if (typeof bca_idx == 'undefined' || ! bca_idx) return 0;

    var count = 0;
    $.ajax({
        url : base_url + "/ajax/board/category_count",
        type : 'get',
        cache : false,
        async : false,
        data : {
            bca_idx: bca_idx
        },
        success:function(res)
        {
            count = res.result;
        }
    })

    return count;
};

APP.BOARD.CATEGORY.postCount = function(bca_idx)
{
    if (typeof bca_idx == 'undefined' || ! bca_idx) return 0;

    var count = 0;
    $.ajax({
        url : base_url + "/ajax/board/category_post_count",
        type : 'get',
        cache : false,
        async : false,
        data : {
            bca_idx: bca_idx
        },
        success:function(res)
        {
            count = res.result;
        }
    });

    return count;
};

APP.BOARD.COMMENT.modify= function( cmt_idx )
{
    APP.POPUP({
        title : '_blank',
        width : 800,
        height : 600,
        url : base_url + '/board/comment/modify/' + cmt_idx
    });
};

APP.BOARD.COMMENT.reply= function( cmt_idx, cmt_num )
{
    APP.POPUP({
        title : '_blank',
        width : 800,
        height : 600,
        url : base_url + '/board/comment/reply/' + cmt_idx + '/' + cmt_num
    });
};

$(function(){
    var $form_post = $('[data-form="post"]');
    if( $form_post.length > 0 )
    {
        $form_post.on('submit', function(){
            $.blockUI({
                css: {width:'25px',top:'49%',left:'49%',border:'0px none',backgroundColor:'transparent',cursor:'wait'},
                message : '<img src="/assets/images/common/ajax-loader.gif" alt="로딩중">',
                baseZ : 10000,
                overlayCSS : {opacity : 0}
            });
        });
    }
});
