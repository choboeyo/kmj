APP.BOARD.keyCheck = function(value) {
    if (value == '') return "게시판 고유키를 입력하세요";
    if (!APP.REGEX.uniqueID.test(value)) return "게시판 고유키는 영어 소문자로 시작하는 3~20 글자로 영어와 숫자만 사용가능합니다.";
    if (APP.BOARD.existCheck(value)) return "이미 존재하는 키 입니다.";

    return true;
};

APP.BOARD.existCheck = function(brd_key) {
    var info = null;
    $.ajax({
        url : base_url + '/ajax/board/info',
        type : 'get',
        async : false,
        cache : false,
        data : {
            brd_key : brd_key,
            is_raw : true
        },
        success:function(res){
            info = res;
        }
    });
    return info;
};

APP.BOARD.CATEGORY.form = function(brd_key, bca_parent, bca_idx) {
    var brd_key = typeof brd_key != 'undefined' && brd_key ? brd_key : null;
    var bca_parent = typeof bca_parent != 'undefined' && bca_parent >= 0 ? bca_parent : null;
    var bca_idx = typeof bca_idx != 'undefined' && bca_idx ? bca_idx : null;

    if(! brd_key )
    {
        alert('게시판이 지정되지 않았습니다.');
        return false;
    }

    if(parseInt(bca_parent) < 0)
    {
        alert('부모 카테고리가 선택되지 않았습니다.');
        return false;
    }

    APP.MODAL.open({
        width: 400,
        height :200,
        header : {
            title : bca_idx ? '카테고리 정보 수정' : '카테고리 추가'
        },
        callback : function(){
            parent.location.reload();
        },
        iframe : {
            method : 'get',
            url : '/admin/board/category_form',
            param : {
                brd_key : brd_key,
                bca_parent : bca_parent,
                bca_idx : bca_idx
            }
        }
    });
};

APP.BOARD.CATEGORY.remove = function(bca_idx) {
    
    if( APP.BOARD.CATEGORY.count(bca_idx) > 0 )
    {
        alert('해당 카테고리의 하위 카테고리가 존재합니다. 하위 카테고리를 먼저 삭제해주세요');
        return false;
    }

    var post_count = APP.BOARD.CATEGORY.postCount(bca_idx);
    if( post_count > 0 )
    {
        if(! confirm('해당 카테고리에 등록된 글이 '+post_count+'건이 있습니다. 삭제를 진행하시겠습니까?' )) {
            return false;
        }
    }

    if(! confirm('해당 카테고리를 삭제하시겠습니까?')) return false;

    $.ajax({
        url : base_url + "/ajax/board/category",
        type : 'DELETE',
        cache : false,
        async: false,
        data : {
            bca_idx : bca_idx
        },
        success:function(res){
            if( res.result )
            {
                alert('카테고리 삭제에 성공하였습니다.');
                location.reload();
            }
            else {
                alert('카테고리 삭제에 실패하였습니다.');
                location.reload();
            }
        }
    })
};

APP.BOARD.EXTRA.form = function(brd_key, bmt_idx)
{
    brd_key = typeof brd_key !='undefined' && brd_key ? brd_key : null;
    bmt_idx = typeof bmt_idx !='undefined' && bmt_idx ? bmt_idx : null;

    if(! brd_key )
    {
        alert('게시판이 지정되지 않았습니다.');
        return false;
    }

    APP.MODAL.open({
        width: 400,
        height :200,
        header : {
            title : bmt_idx ? '입력필드 수정' : '입력필드 추가'
        },
        callback : function(){
            parent.location.reload();
        },
        iframe : {
            method : 'get',
            url : '/admin/board/extra_form',
            param : {
                brd_key : brd_key,
                bmt_idx : bmt_idx
            }
        }
    });
};

APP.BOARD.EXTRA.remove = function(brd_key,bmt_idx)
{
    brd_key = typeof brd_key !='undefined' && brd_key ? brd_key : null;
    bmt_idx = typeof bmt_idx !='undefined' && bmt_idx ? bmt_idx : null;

    if(! bmt_idx )
    {
        alert('잘못된 접근입니다.');
        return false;
    }

    if(! confirm('해당 필드로 등록된 글이 있을경우, 해당 필드값도 같이 사라집니다. 계속 진행 하시겠습니까?')) return false;

    $.ajax({
        url : base_url + "/ajax/board/extra",
        type : 'DELETE',
        cache : false,
        async: false,
        data : {
            brd_key : brd_key,
            bmt_idx : bmt_idx
        },
        success:function(res){
            if( res.result )
            {
                alert('입력필드 삭제에 성공하였습니다.');
                location.reload();
            }
            else {
                alert('입력필드 삭제에 실패하였습니다.');
                location.reload();
            }
        }
    })
};


