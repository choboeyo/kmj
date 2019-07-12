/***********************************************************************************
 * AJAX Error 및 BlockUI 처리
 ***********************************************************************************/
$(function() {
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

/***********************************************************************************************************************
 * 전체체크박스 / 체크박스 연동
 ***********************************************************************************************************************/
$(function() {
    $(document).on('change', '[data-checkbox]', function() {
        var $check = $(this);
        var is_all = $check.data('checkbox-all') != null ? true : false;
        var name = $check.data('checkbox');
        var checked = $check.prop('checked');
        var $allCheck = is_all ? $check : $('[data-checkbox="'+name+'"][data-checkbox-all]');

        if( is_all ) {
            $('[data-checkbox="'+name+'"]').prop('checked', checked );
        }
        else {
            $allCheck.prop('checked', $('[data-checkbox="'+name+'"]').not('[data-checkbox-all]').length ==  $('[data-checkbox="'+name+'"]:checked').not('[data-checkbox-all]').length);
        }
    });
});

/***********************************************************************************************************************
 * 숫자 3자리마다 Comma 자동 입력
 ***********************************************************************************************************************/
$(function() {
    $(document).on('keypress', '[data-number-format]', function(e) {
        $(this).val( $(this).val().trim().unNumberFormat().numberFormat() );
    })
});

/***********************************************************************************************************************
 * 숫자만 입력가능한 Input
 ***********************************************************************************************************************/
$(function() {
    $(document).on('keypress', '[data-number-only]', function(e) {
        if (e.which != 8 && e.which != 0 &&  e.which != 45 && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    })
});

/***********************************************************************************************************************
 * 높이 자동조절되는 Textarea
 ***********************************************************************************************************************/
$(function() {
    $(document).on('keyup','textarea[data-autosize]', function(e) {
        autosize($(this));
    });
    $('textarea[data-autosize]').keyup();
});

$(function() {
    /***********************************************************************************************************************
     * 핸드폰 번호 Input
     ***********************************************************************************************************************/
    $('body').on('keypress', '[data-regex="phone-number"]', function(e){
        if (e.which != 8 && e.which != 0 &&  e.which != 45 && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    }).on('blur','[data-regex="phone-number"]', function(e){
        if($(this).val() == '') return;
        var transNum = $(this).val().regex('phone');
        if( transNum === false ) {
            toastr.error('유효하지 않은 전화번호 입니다.');
            $(this).val("");
            $(this).focus();
            return;
        }
        $(this).val(transNum);
    });

    /***********************************************************************************************************************
     * 전화번호 Input
     ***********************************************************************************************************************/
    $('body').on('blur', '[data-regex="tel-number"]', function(e){
        if($(this).val() == '') return;
        var transNum = $(this).val().regex('tel');
        if( transNum === false ) {
            toastr.error('유효하지 않은 전화번호 입니다.');
            $(this).val("");
            $(this).focus();
            return;
        }
        $(this).val(transNum);
    });

    /***********************************************************************************************************************
     * 이메일주소 Input
     ***********************************************************************************************************************/
    $('body').on('blur', '[data-regex="email-address"]', function(e){
        if($(this).val() == '') return;
        var trans_num = $(this).val().regex('email');

        if(! trans_num) {
            toastr.error('유효하지 않은 이메일주소 입니다.');
            $(this).val("");
            $(this).focus();
        }
    });
});

