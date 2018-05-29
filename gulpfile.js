var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var minifycss  = require('gulp-minify-css');
var sass = require('gulp-sass');
var theme = {};
theme.global = {};
theme.desktop = {};
theme.mobile = {};
theme.admin = {};

// PC용 테마용 파일들
theme.desktop.title = "desktop";
theme.desktop.css = [
    "node_modules/bootstrap/dist/css/bootstrap.css",
    "_src/desktop/scss/desktop.scss"
];
theme.desktop.js = [
    "node_modules/bootstrap/dist/js/bootstrap.js",
];

// 모바일 테마용 파일들
theme.mobile.title = "mobile";
theme.mobile.css = [
];
theme.mobile.js = [
];

// 관리자 페이지용 파일들
theme.admin.title = "admin";
theme.admin.css = [
    "_src/plugins/jquery-ui-1.12.1.custom/jquery-ui.css",
    "node_modules/bootstrap/dist/css/bootstrap.css",
    "_src/plugins/fontawesome5/fontawesome.scss",
    "_src/plugins/fontawesome5/fa-regular.scss",
    "node_modules/ax5ui-modal/dist/ax5modal.css",
    "node_modules/ax5ui-mask/dist/ax5mask.css",
    "_src/admin/scss/admin.scss"
];
theme.admin.js = [
    "node_modules/bootstrap/dist/js/bootstrap.js",
    "node_modules/ax5core/dist/ax5core.js",
    "node_modules/ax5ui-mask/dist/ax5mask.js",
    "node_modules/ax5ui-modal/dist/ax5modal.js",
    "node_modules/nicescroll/dist/jquery.nicescroll.js",
    "_src/plugins/jquery-ui-1.12.1.custom/jquery-ui.js",
    "_src/admin/js/jquery.formatter.js",
    "_src/admin/js/jquery.tmpl.js",
    "_src/admin/js/admin.js",
    "_src/admin/js/modules/board.js",
    "_src/admin/js/modules/faq.js",
    "_src/admin/js/modules/member.js",
];

// 공용으로 로드할 파일들
theme.global.css = [
    "node_modules/reset-css/reset.css",
    "_src/common/css/global.css",
    "_src/common/css/toastr.css",
];
theme.global.js = [
    "node_modules/jquery/dist/jquery.js",
    "_src/common/js/jquery.blockUI.js",
    "_src/common/js/jquery.cookie.js",
    "_src/common/js/toastr.js",
    "_src/common/js/global.js",
    "_src/common/js/member.js",
    "_src/common/js/board.js",
];


gulp.task('minify-desktop-css', function () {
    return gulp.src( theme.global.css.concat(theme.desktop.css))
        .pipe(sass({outputStyle: 'compact'}))
        .pipe(concat( theme.desktop.title + '.min.css')) //병합하고
        .pipe(minifycss().on('error', function(e){console.log(e);}))
        .pipe(gulp.dest('public_html/assets/css'));
});

// Javascript 합치기 실행
gulp.task('minify-desktop-js', [], function(){
    return gulp.src( theme.global.js.concat(theme.desktop.js))
        .pipe(uglify())
        .pipe(concat( theme.desktop.title + '.min.js'))
        .pipe(gulp.dest('public_html/assets/js'));
});

gulp.task('minify-mobile-css', function () {
    return gulp.src( theme.global.css.concat(theme.mobile.css))
        .pipe(sass({outputStyle: 'compact'}))
        .pipe(concat( theme.mobile.title + '.min.css')) //병합하고
        .pipe(minifycss().on('error', function(e){console.log(e);}))
        .pipe(gulp.dest('public_html/assets/css'));
});

gulp.task('minify-mobile-js', [], function(){
    return gulp.src( theme.global.js.concat(theme.mobile.js))
        .pipe(uglify())
        .pipe(concat( theme.mobile.title + '.min.js'))
        .pipe(gulp.dest('public_html/assets/js'));
});


gulp.task('minify-admin-css', function () {
    return gulp.src( theme.global.css.concat(theme.admin.css))
        .pipe(sass({outputStyle: 'compact'}))
        .pipe(concat( theme.admin.title + '.min.css')) //병합하고
        .pipe(minifycss().on('error', function(e){console.log(e);}))
        .pipe(gulp.dest('public_html/assets/css'));
});

gulp.task('minify-admin-js', [], function(){
    return gulp.src( theme.global.js.concat( theme.admin.js))
        .pipe(uglify())
        .pipe(concat( theme.admin.title + '.min.js'))
        .pipe(gulp.dest('public_html/assets/js'));
});

gulp.task('mobile-minify', ['minify-mobile-js', 'minify-mobile-css']);
gulp.task('desktop-minify', ['minify-desktop-js', 'minify-desktop-css']);
gulp.task('admin-minify', ['minify-admin-js', 'minify-admin-css']);
gulp.task('default', ['minify-desktop-js', 'minify-desktop-css', 'minify-mobile-js', 'minify-mobile-css','minify-admin-js', 'minify-admin-css']);
