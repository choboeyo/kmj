APP.MASK = new ax5.ui.mask({
    zIndex: 1000}
);
APP.MASK2 = new ax5.ui.mask({
    zIndex: 2000
});
APP.modal = new ax5.ui.modal({
    absolute: true,
    iframeLoadingMsg: '<i class="fal fa-spinner"></i>'
});
APP.modal2 = new ax5.ui.modal({
    absolute: true,
    iframeLoadingMsg: '<i class="fal fa-spinner"></i>'
});
APP.MODAL = function() {
    var modalCallback = {};

    var defaultCss = {
        width: 400,
        height: 400,
        position: {
            left: "center",
            top: "middle"
        }
    };

    var defaultOption = $.extend(true, {}, defaultCss, {
        iframeLoadingMsg: "",
        iframe: {
            method: "get",
            url: "#"
        },
        closeToEsc: true,
        onStateChanged: function onStateChanged() {
            // mask
            if (this.state === "open") {
                APP.MASK.open();
            } else if (this.state === "close") {
                APP.MASK.close();
            }
        },
        animateTime: 100,
        zIndex: 1001,
        absolute: true,
        fullScreen: false,
        header: {
            title: "새로운 윈도우",
            btns: {
                close: {
                    label: '<i class="far fa-times"></i>', onClick: function onClick() {
                        APP.MODAL.callback();
                    }
                }
            }
        }
    });

    var open = function(modalConfig) {

        modalConfig = $.extend(true, {}, defaultOption, modalConfig);
        $(document.body).addClass("modalOpened");

        this.modalCallback = modalConfig.callback;
        this.modalSendData = modalConfig.sendData;

        APP.modal.open(modalConfig);
    };

    var css = function css(modalCss) {
        modalCss = $.extend(true, {}, defaultCss, modalCss);
        APP.modal.css(modalCss);
    };
    var align = function align(modalAlign) {
        APP.modal.align(modalAlign);
    };
    var close = function close(data) {
        APP.modal.close();
        setTimeout(function () {
            $(document.body).removeClass("modalOpened");
        }, 500);
    };
    var minimize = function minimize() {
        APP.modal.minimize();
    };
    var maximize = function maximize() {
        APP.modal.maximize();
    };
    var callback = function callback(data) {
        if (this.modalCallback) {
            this.modalCallback(data);
        }
        this.close(data);
    };
    var getData = function getData() {
        if (this.modalSendData) {
            return this.modalSendData();
        }
    };

    return {
        "open": open,
        "css": css,
        "align": align,
        "close": close,
        "minimize": minimize,
        "maximize": maximize,
        "callback": callback,
        "modalCallback": modalCallback,
        "getData": getData
    };
}();
APP.MODAL2 = function() {
    var modalCallback = {};

    var defaultCss = {
        width: 400,
        height: 400,
        position: {
            left: "center",
            top: "middle"
        }
    };

    var defaultOption = $.extend(true, {}, defaultCss, {
        iframeLoadingMsg: "",
        iframe: {
            method: "get",
            url: "#"
        },
        closeToEsc: true,
        onStateChanged: function onStateChanged() {
            // mask
            if (this.state === "open") {
                APP.MASK2.open();
            } else if (this.state === "close") {
                APP.MASK2.close();
            }
        },
        animateTime: 100,
        zIndex: 2001,
        absolute: true,
        fullScreen: false,
        header: {
            title: "새로운 윈도우",
            btns: {
                close: {
                    label: '<i class="far fa-times"></i>', onClick: function onClick() {
                        APP.MODAL2.callback();
                    }
                }
            }
        }
    });

    var open = function(modalConfig) {

        modalConfig = $.extend(true, {}, defaultOption, modalConfig);
        $(document.body).addClass("modalOpened");

        this.modalCallback = modalConfig.callback;
        this.modalSendData = modalConfig.sendData;

        APP.modal2.open(modalConfig);
    };

    var css = function css(modalCss) {
        modalCss = $.extend(true, {}, defaultCss, modalCss);
        APP.modal2.css(modalCss);
    };
    var align = function align(modalAlign) {
        APP.modal2.align(modalAlign);
    };
    var close = function close(data) {
        APP.modal2.close();
        setTimeout(function () {
            $(document.body).removeClass("modalOpened");
        }, 500);
    };
    var minimize = function minimize() {
        APP.modal2.minimize();
    };
    var maximize = function maximize() {
        APP.modal2.maximize();
    };
    var callback = function callback(data) {
        if (this.modalCallback) {
            this.modalCallback(data);
        }
        this.close(data);
    };
    var getData = function getData() {
        if (this.modalSendData) {
            return this.modalSendData();
        }
    };

    return {
        "open": open,
        "css": css,
        "align": align,
        "close": close,
        "minimize": minimize,
        "maximize": maximize,
        "callback": callback,
        "modalCallback": modalCallback,
        "getData": getData
    };
}();