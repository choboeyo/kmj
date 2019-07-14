/**********************************************************************************************************************
 * 회원정보 팝업
 *********************************************************************************************************************/
APP.MEMBER.POP_INFO_ADMIN = function(mem_idx) {
    if( typeof mem_idx == 'undefined' || ! mem_idx  ) {
        alert('잘못된 접근입니다.');
        return false;
    }

    APP.MODAL.open({
        width: 800,
        height :600,
        header : {
            title : '회원 정보'
        },
        callback : function(){
            APP.MODAL.close();
            grid.refresh();
        },
        iframe : {
            method : 'get',
            url : '/admin/members/info/' + mem_idx,
            param : {}
        }
    });

};

/**********************************************************************************************************************
 * 회원 비밀번호 변경 팝업
 *********************************************************************************************************************/
APP.MEMBER.POP_PASSWORD_ADMIN = function(mem_idx) {
    if( typeof mem_idx == 'undefined' || ! mem_idx  ) {
        alert('잘못된 접근입니다.');
        return false;
    }

    APP.MODAL.open({
        width: 800,
        height :600,
        header : {
            title : '비밀번호 변경'
        },
        callback : function(){
            APP.MODAL.close();
            grid.refresh();
        },
        iframe : {
            method : 'get',
            url : '/admin/members/password/' + mem_idx,
            param : {}
        }
    });

};

/**********************************************************************************************************************
 * 회원 정보수정 팝업
 *********************************************************************************************************************/
APP.MEMBER.POP_MODIFY_ADMIN = function(mem_idx) {
    if( typeof mem_idx == 'undefined' || ! mem_idx  ) {
        alert('잘못된 접근입니다.');
        return false;
    }

    APP.MODAL.open({
        width: 800,
        height :600,
        header : {
            title : '회원 정보 수정'
        },
        callback : function(){
            APP.MODAL.close();
            grid.refresh();
        },
        iframe : {
            method : 'get',
            url : '/admin/members/modify/' + mem_idx,
            param : {}
        }
    });

};

/**********************************************************************************************************************
 * 회원 포인트 정보 팝업
 *********************************************************************************************************************/
APP.MEMBER.POP_POINT_ADMIN = function(mem_idx) {
    if( typeof mem_idx == 'undefined' || ! mem_idx  ) {
        alert('잘못된 접근입니다.');
        return false;
    }

    APP.MODAL.open({
        width: 800,
        height :600,
        header : {
            title : '회원 포인트 관리'
        },
        callback : function(){
            APP.MODAL.close();
            grid.refresh();
        },
        iframe : {
            method : 'get',
            url : '/admin/members/point/' + mem_idx,
            param : {}
        }
    });
};

/**********************************************************************************************************************
 * 회원 포인트 추가 팝업
 *********************************************************************************************************************/
APP.MEMBER.POP_POINT_FORM_ADMIN = function(mem_idx) {
    var mem_idx = typeof mem_idx != 'undefined' && mem_idx ? mem_idx : null;
    if(! mem_idx) {
        alert('잘못된 접근입니다.');
        return;
    }

    APP.MODAL2.open({
        width: 410,
        height :200,
        header : {
            title : '회원 포인트 추가'
        },
        callback : function(){
            APP.MODAL2.close();
            grid.refresh();
        },
        iframe : {
            method : 'get',
            url : '/admin/members/point_form/' + mem_idx
        }
    });
};

/**********************************************************************************************************************
 * 회원 STATUS 변경
 *********************************************************************************************************************/
APP.MEMBER.STATUS_CHANGE = function(mem_idx, current_status, change_status) {
    if( typeof mem_idx == 'undefined' || ! mem_idx  || typeof current_status == 'undefined' || ! current_status || typeof change_status == 'undefined' || ! change_status  ) {
        alert(LANG.common_msg_invalid_access);
        return false;
    }
    var change_status_msg = '';
    if( change_status == 'Y' ) change_status_msg = LANG.member_status_y;
    else if (change_status == 'N') change_status_msg = LANG.member_status_n;
    else if (change_status == 'D') change_status_msg = LANG.member_status_d;
    else if (change_status == 'H') change_status_msg = LANG.member_status_h;
    else {
        alert(LANG.common_msg_invalid_access);
        return false;
    }

    if( ! confirm('해당 회원의 상태를 [' + change_status_msg + '] 상태로 변경합니까?') ) return;
    $.ajax({
        url : base_url + '/admin/ajax/members/status',
        type : 'POST',
        async : false,
        cache : false,
        data : {
            mem_idx : mem_idx,
            current_status : current_status,
            change_status : change_status
        },
        success:function(){
            toastr.success('지정한 회원의 상태를 [' + change_status_msg + '] 상태로 변경하였습니다.');
            grid.refresh();
        }
    })
};

$(function(){

});
