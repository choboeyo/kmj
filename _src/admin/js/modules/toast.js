APP.TOAST = {
    confirm : function(msg) {
        APP.toast.push({
            theme : 'default',
            icon : '<i class="far fa-bell"></i>',
            msg : msg
        });
    },
    error : function(msg) {
        APP.toast.push({
            theme : 'danger',
            icon : '<i class="far fa-exclamation-circle"></i>',
            msg : msg
        });
    },
    success: function(msg) {
        APP.toast.push({
            theme : 'success',
            icon : '<i class="far fa-check-circle"></i>',
            msg : msg
        })
    }
};