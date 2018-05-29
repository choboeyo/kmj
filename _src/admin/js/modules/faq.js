var faq = {};
faq.form = function(fac_idx, faq_idx)
{
    var faq_idx = (typeof faq_idx == 'string' || typeof faq_idx == 'number' ) ? faq_idx : null;
    var fac_idx = (typeof fac_idx == 'string' || typeof fac_idx == 'number' ) ? fac_idx : null;
    if(! fac_idx) {
        alert('FAQ 분류 정보가 없습니다.');
        return false;
    }

    APP.MODAL.open({
        width: 800,
        height :650,
        header : {
            title : faq_idx ? 'FAQ 정보 수정' : 'FAQ 추가'
        },
        callback : function(){
            location.reload();
        },
        iframe : {
            method : 'get',
            url : '/admin/management/faq_form',
            param : {
                fac_idx : fac_idx,
                faq_idx : faq_idx
            }
        }
    });
};
faq.remove = function(faq_idx) {
    if(typeof faq_idx == 'undefined' || ! faq_idx || faq_idx.trim() == '') {
        alert('잘못된 접근입니다.');
    }

    if(! confirm('해당 FAQ를 삭제하시겠습니까?')) return false;

    $.ajax({
        url : '/ajax/faq/info',
        type : 'delete',
        async:false,
        cache:false,
        data:{faq_idx:faq_idx},
        success:function(res){
            alert('FAQ가 삭제되었습니다.');
            location.reload();
        }
    });
};

/**
 * FAQ 분류
 * @type {{}}
 */
faq.category = {};
faq.category.form = function(fac_idx)
{
    var fac_idx = (typeof fac_idx == 'string' || typeof fac_idx == 'number' ) ? fac_idx : null;
    APP.MODAL.open({
        width: $(window).width() > 600 ? 600 : $(window).width(),
        height :250,
        header : {
            title : fac_idx ? 'FAQ 분류 정보 수정' : 'FAQ 분류 추가'
        },
        callback : function(){
            location.reload();
        },
        iframe : {
            method : 'get',
            url : '/admin/management/faq_category_form',
            param : {
                fac_idx : fac_idx
            }
        }
    });
};
faq.category.exist = function(fac_idx) {
    if(typeof fac_idx == 'undefined' || ! fac_idx || fac_idx.trim() == '') return false;
    var result = false;
    $.ajax({
        url : '/ajax/faq/category',
        type:'get',
        async:false,
        cache:false,
        data:{fac_idx:fac_idx},
        success:function (res) {
            result = !(res && typeof res.fac_idx != 'undefined' && res.fac_idx);
        }
    });
    return result;
};
faq.category.remove = function(fac_idx) {
    if(typeof fac_idx == 'undefined' || ! fac_idx || fac_idx.trim() == '') {
        alert('잘못된 접근입니다.');
    }
    var count = 0;
    $.ajax({
        url : '/ajax/faq/lists',
        type : 'get',
        async:false,
        cache: false,
        data : {fac_idx:fac_idx},
        success:function(res){
            count = res.total_count;
        }
    });

    var msg = ( count > 0 ) ? '해당 FAQ 분류에 ' + count + '개의 FAQ 목록이 등록되어 있습니다.\nFAQ 분류을 삭제할시 등록된 FAQ 목록도 같이 삭제됩니다.\n\n계속 하시겠습니까?' : 'FAQ 분류을 삭제하시겠습니까?';
    if(! confirm(msg)) return false;

    $.ajax({
        url : '/ajax/faq/category',
        type : 'delete',
        async:false,
        cache:false,
        data:{fac_idx:fac_idx},
        success:function(res){
            alert('FAQ 분류가 삭제되었습니다.');
            location.href= base_url + "/admin/management/faq";
        }
    });
};


