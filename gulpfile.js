let fs = require('fs'),
    gulp = require('gulp'),
    concat = require('gulp-concat'),
    minify = require('gulp-minify'),
    cleanCSS  = require('gulp-clean-css'),
    size = require('gulp-size'),
    sass = require('gulp-sass'),
    sourcemaps = require('gulp-sourcemaps');

let packageFile = JSON.parse(fs.readFileSync('source.info.json'));
let theme = packageFile.source;
let destPath = packageFile.destPath;
let watchPath = packageFile.watch;

gulp.task('minify-desktop-css', function () {
    let dest = destPath.root + "/" + destPath.assets + "/" + destPath.css;
    let fileName = "desktop.min.css";

    return gulp.src( theme.desktop.css )
        .pipe(sourcemaps.init())
        .pipe( sass({outputStyle: 'compact'}).on('error', sass.logError))
        .pipe( concat(fileName)) //병합하고
        .pipe(cleanCSS().on('error', function(e){console.log(e);}))
        .pipe(size({ gzip: true, showFiles: true }))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(dest));
});

// Javascript 합치기 실행
gulp.task('minify-desktop-js', function(){
    let dest = destPath.root + "/" + destPath.assets + "/" + destPath.js;
    let fileName = "desktop.js";

    return gulp.src( theme.commonJs.concat( theme.desktop.js ) )
        .pipe(sourcemaps.init())
        .pipe(concat(fileName))
        .pipe(minify({ext: {min : '.min.js'},noSource:true}))
        .pipe(size({ gzip: true, showFiles: true }))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(dest));
});

gulp.task('minify-mobile-css', function () {
    let dest = destPath.root + "/" + destPath.assets + "/" + destPath.css;
    let fileName = "mobile.min.css";

    return gulp.src( theme.mobile.css )
        .pipe(sourcemaps.init())
        .pipe( sass({outputStyle: 'compact'}).on('error', sass.logError))
        .pipe( concat(fileName)) //병합하고
        .pipe(cleanCSS().on('error', function(e){console.log(e);}))
        .pipe(size({ gzip: true, showFiles: true }))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(dest));
});

gulp.task('minify-mobile-js', function(){
    let dest = destPath.root + "/" + destPath.assets + "/" + destPath.js;
    let fileName = "mobile.js";

    return gulp.src( theme.commonJs.concat( theme.mobile.js ) )
        .pipe(sourcemaps.init())
        .pipe(concat(fileName))
        .pipe(minify({ext: {min : '.min.js'},noSource:true}))
        .pipe(size({ gzip: true, showFiles: true }))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(dest));
});


gulp.task('minify-admin-css', function () {
    let dest = destPath.root + "/" + destPath.assets + "/" + destPath.css;
    let fileName = "admin.min.css";

    return gulp.src( theme.admin.css )
        .pipe(sourcemaps.init())
        .pipe( sass({outputStyle: 'compact'}).on('error', sass.logError))
        .pipe( concat(fileName)) //병합하고
        .pipe(cleanCSS().on('error', function(e){console.log(e);}))
        .pipe(size({ gzip: true, showFiles: true }))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(dest));
});

gulp.task('minify-admin-js', function(){
    let dest = destPath.root + "/" + destPath.assets + "/" + destPath.js;
    let fileName = "admin.js";

    return gulp.src( theme.commonJs.concat( theme.admin.js ) )
        .pipe(sourcemaps.init())
        .pipe(concat(fileName))
        .pipe(minify({ext: {min : '.min.js'},noSource:true}))
        .pipe(size({ gzip: true, showFiles: true }))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(dest));
});

gulp.task('scss-watch', function() {
    gulp.watch(watchPath.desktop.css, gulp.series(['minify-desktop-css']));
    //gulp.watch(watchPath.desktop.js, ['minify-desktop-js']);
    gulp.watch(watchPath.mobile.css, gulp.series(['minify-mobile-css']));
    //gulp.watch(watchPath.mobile.js, ['minify-mobile-js']);
    gulp.watch(watchPath.admin.css, gulp.series(['minify-admin-css']));
    //gulp.watch(watchPath.admin.js, ['minify-admin-js']);
});

gulp.task('default', gulp.series(['minify-desktop-js', 'minify-desktop-css', 'minify-mobile-js', 'minify-mobile-css','minify-admin-js', 'minify-admin-css']));
