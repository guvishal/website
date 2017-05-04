var gulp = require('gulp');
var livereload = require('gulp-livereload')
var uglify = require('gulp-uglifyjs');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var sourcemaps = require('gulp-sourcemaps');
var imagemin = require('gulp-imagemin');
var pngquant = require('imagemin-pngquant');
var browserSync = require('browser-sync');
var reload = browserSync.reload;

/*****************************************************************************************************************
*
* This taks is use to optimize the images by using gulp task.
*
*****************************************************************************************************************/

gulp.task('imagemin', function () {
    return gulp.src('./images/*')
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()]
        }))
        .pipe(gulp.dest('./images'));
});

/**************************************************************************************************************
*
* This task is use to create style.css file.
* Also it automatically adds browser vendor prefix to style file.
*
**************************************************************************************************************/

gulp.task('sass', function () {
  gulp.src('./sass/**/*.scss')
    .pipe(sourcemaps.init())
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
        .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 7', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('./'));
});

/**************************************************************************************************************
*
* This task is use to merge all the JS files and minify them.
*
**************************************************************************************************************/

gulp.task('uglify', function() {
  gulp.src('./lib/*.js')
    .pipe(uglify('script.min.js'))
    .pipe(gulp.dest('./js'))
});

/**************************************************************************************************************
*
* This task is use to sync with your browser.
*
**************************************************************************************************************/

gulp.task('browser-sync',function(){
    var file=[
        './style.css',
        './*.php',
        './**/*.php',
        './js/*.js',
    ];

    browserSync.init(file,{
        proxy: "localhost/vishal/",
        notify: false
    });
});

/**************************************************************************************************************
*
* This task is use for Stage environment 
*
**************************************************************************************************************/

gulp.task('stage', ['browser-sync'], function(){
    livereload.listen();
    gulp.watch('./sass/**/*.scss', ['sass']);
    gulp.watch('./lib/*.js', ['uglify']);
    gulp.watch(['./style.css', './*.php', './js/*.js', './parts/**/*.php'], function (files){
        livereload.changed(files)
    });   
});


/**************************************************************************************************************
*
* This task is use for live environment 
*
**************************************************************************************************************/

gulp.task('prod', function(){
    gulp.watch('./sass/**/*.scss', ['sass']);
    gulp.watch('./lib/*.js', ['uglify']);
    gulp.watch(['./style.css', './*.php', './js/*.js', './parts/**/*.php']);   
});