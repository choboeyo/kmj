APP.MEMBER = {};

/**
 * 회원 관련 자바스크립트 초기화
 */
APP.MEMBER.init = function() {
    APP.MEMBER.InitLoginForm();   // 로그인폼 init
    APP.MEMBER.initCheckExist();
    APP.MEMBER.InitRegisterForm();
    APP.MEMBER.InitMemberModifyForm();
};

APP.MEMBER.InitRegisterForm = function() {
    $('[data-form="form-register"]').submit(function(e) {
        e.preventDefault();
        var $form = $(this);
        $.ajax({
            type : 'PUT',
            data : $form.serialize(),
            url : base_url + '/ajax/members/info',
            success:function(res){
                if(res.result == true) {
                    alert(LANG.member_join_success);
                    location.href = base_url + "/members/login";
                }
            }
        });
    });
};

APP.MEMBER.InitMemberModifyForm = function() {
    $('[data-form="form-member-modify"]').submit(function(e) {
        e.preventDefault();
        var $form = $(this);
        $.ajax({
            type : 'POST',
            data : $form.serialize(),
            url : base_url + '/ajax/members/info',
            success:function(res){
                if(res.result == true) {
                    alert(res.message);
                    location.reload();
                }
            }
        });
    });
};

/**
 * 로그인 폼 초기화
 * @constructor
 */
APP.MEMBER.InitLoginForm = function() {
    $('[data-role="form-login"]').submit(function (e) {
        e.preventDefault();
        var $form = $(this);
        var elementLoginID = $form.find('[name="login_id"]');
        var elementLoginPass = $form.find('[name="login_pass"]');

        if(elementLoginID.val().trim() == '')
        {
            alert(LANG.member_login_userid_required);
            elementLoginID.focus();
            return false;
        }

        if(elementLoginPass.val().trim() == '')
        {
            alert(LANG.member_login_password_required);
            elementLoginPass.focus();
            return false;
        }

        $.ajax({
            url : base_url + 'ajax/members/login',
            type : "POST",
            data : $form.serialize(),
            success:function(res) {
                if (res.result == true) {
                    location.href = res.reurl ? res.reurl : base_url;
                }
            },
            error : function(e){
                elementLoginPass.val('');
            }
        })
    });
};

/**
 * 중복확인 버튼 이벤트 연결
 */
APP.MEMBER.initCheckExist = function() {
    $('[data-toggle="check-member-exist"]').each(function(){
        var $btn = $(this);

        $btn.on('click', function(){
            var $target = $("#" + $btn.data('target'));
            var key = $btn.data('check');
            var value = $target.val();

            if( typeof value == 'undefined' || ! value || ! value.trim() )
            {
                alert(LANG.member_join_user_id_required );
                $target.focus();
                return false;
            }

            var wordCheck = APP.MEMBER.denyWordCheck(key,value);
            if( wordCheck == 'VALID_EMAIL' )
            {
                alert(LANG.member_join_no_valid_email_address );
                $target.focus();
                return false;
            }
            else if(! wordCheck)
            {
                alert(LANG.member_join_user_id_contains_deny_word );
                $target.focus();
                return false;
            }

            if( APP.MEMBER.getInfo(key, value) )
            {
                alert(LANG.member_join_user_id_already_exists );
                $target.focus();
                return false;
            }

            alert(LANG.member_join_user_id_available  );
            return true;
        });
    });

};

/**
 * 사용자의 정보 가져오기
 * @param key   가져올 기준 키
 * @param value 키 값
 * @returns {*}
 */
APP.MEMBER.getInfo = function(key, value) {
    var info = null;
    $.ajax({
        url : base_url + '/ajax/members/info',
        type : 'get',
        async : false,
        cache : false,
        data : {
            key : key,
            value : value
        },
        success:function(res){
            info = res.result;
        }
    });
    return info;
};

/**
 * 아이디와 닉네임 금지단어 사용여부 체크
 * @param key   아이디/닉네임
 * @param value 체크할 값
 * @returns {*}
 */
APP.MEMBER.denyWordCheck = function(key,value) {
    var result = null;
    $.ajax({
        url : base_url + '/ajax/members/word_check',
        type : 'get',
        async : false,
        cache : false,
        data : {
            key:key,
            value : value
        },
        success:function(res) {
            result = res.result;
        }
    });
    return result;
};


APP.MEMBER.POP_CHANGE_PHOTO = function() {
    APP.POPUP({
        url : '/members/photo_change',
        width : 600,
        height :150
    });
};

$(document).ready(APP.MEMBER.init);