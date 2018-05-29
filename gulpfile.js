var theme = {
    // 데스크탑 테마용
    desktop : {
        css : [
            "_src/desktop/scss/desktop.scss"
        ],
        js : [
            "_src/desktop/js/common.js"
        ]
    },

    // 모바일 테마용
    mobile : {
        css : [
            "_src/mobile/scss/mobile.scss"
        ],
        js : [
            "_src/mobile/js/mobile.js"
        ]
    },

    // ADMIN 테마용
    admin : {
        css: [
            "_src/admin/scss/admin.scss"
        ],
        js: [
            "_src/plugins/ax5core/ax5core.js",
            "_src/plugins/ax5ui-mask/ax5mask.js",
            "_src/plugins/ax5ui-modal/ax5modal.js",
            "_src/plugins/nicescroll/jquery.nicescroll.js",
            "_src/plugins/jquery-datetimepicker/jquery.datetimepicker.full.js",
            "_src/plugins/jquery-ui-1.12.1.custom/jquery-ui.js",
            "_src/admin/js/jquery.formatter.js",
            "_src/admin/js/jquery.tmpl.js",
            "_src/admin/js/admin.js",
            "_src/admin/js/modules/board.js",
            "_src/admin/js/modules/faq.js",
            "_src/admin/js/modules/member.js",
        ]
    },
    commonJs : [
        "_src/plugins/jquery-blockUI/jquery.blockUI.js",
        "_src/plugins/jquery-cookie/jquery.cookie.js",
        "_src/plugins/toastr/toastr.js",
        "_src/common/js/global.js",
        "_src/common/js/member.js",
        "_src/common/js/board.js",
    ]
};

var gulp = require('gulp'),
    concat = require('gulp-concat'),
    minify = require('gulp-minify'),
    cleanCSS  = require('gulp-clean-css'),
    size = require('gulp-size'),
    sass = require('gulp-sass');


gulp.task('minify-desktop-css', function () {
    return gulp.src( theme.desktop.css )
        .pipe(sass({outputStyle: 'compact'}))
        .pipe(concat( 'desktop.min.css')) //병합하고
        .pipe(cleanCSS().on('error', function(e){console.log(e);}))
        .pipe(size({ gzip: true, showFiles: true }))
        .pipe(gulp.dest('public_html/assets/css'));
});

// Javascript 합치기 실행
gulp.task('minify-desktop-js', [], function(){
    return gulp.src( theme.commonJs.concat( theme.desktop.js ) )
        .pipe(concat('admin.js'))
        .pipe(minify({
            ext: {
                min : '.min.js'
            },
            noSource:true
        }))
        .pipe(size({ gzip: true, showFiles: true }))
        .pipe(gulp.dest('public_html/assets/js'));
});

gulp.task('minify-mobile-css', function () {
    return gulp.src( theme.mobile.css )
        .pipe(sass({outputStyle: 'compact'}))
        .pipe(concat( 'mobile.min.css')) //병합하고
        .pipe(cleanCSS().on('error', function(e){console.log(e);}))
        .pipe(size({ gzip: true, showFiles: true }))
        .pipe(gulp.dest('public_html/assets/css'));
});

gulp.task('minify-mobile-js', [], function(){
    return gulp.src( theme.commonJs.concat( theme.mobile.js ) )
        .pipe(concat( 'mobile.js'))
        .pipe(minify({
            ext: {
                min : '.min.js'
            },
            noSource:true
        }))
        .pipe(size({ gzip: true, showFiles: true }))
        .pipe(gulp.dest('public_html/assets/js'));
});


gulp.task('minify-admin-css', function () {
    return gulp.src( theme.admin.css )
        .pipe(sass({outputStyle: 'compact'}))
        .pipe(concat('admin.min.css')) //병합하고
        .pipe(cleanCSS().on('error', function(e){console.log(e);}))
        .pipe(size({ gzip: true, showFiles: true }))
        .pipe(gulp.dest('public_html/assets/css'));
});

gulp.task('minify-admin-js', [], function(){
    return gulp.src( theme.commonJs.concat( theme.admin.js ) )
        .pipe(concat( 'admin.js') )
        .pipe(minify({
            ext: {
                min : '.min.js'
            },
            noSource:true
        }))
        .pipe(size({ gzip: true, showFiles: true }))
        .pipe(gulp.dest('public_html/assets/js'));
});

gulp.task('mobile-minify', ['minify-mobile-js', 'minify-mobile-css']);
gulp.task('desktop-minify', ['minify-desktop-js', 'minify-desktop-css']);
gulp.task('admin-minify', ['minify-admin-js', 'minify-admin-css']);
gulp.task('default', ['minify-desktop-js', 'minify-desktop-css', 'minify-mobile-js', 'minify-mobile-css','minify-admin-js', 'minify-admin-css']);
