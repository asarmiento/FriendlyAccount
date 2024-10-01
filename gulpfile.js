var gulp = require('gulp'),
    sass = require('gulp-sass'),
    watch = require('gulp-watch'),
    sourcemaps = require('gulp-sourcemaps'),
    cssnano = require('gulp-cssnano');

var config = {
    scssDir: './resources/assets/sass',
    cssDir: './public/css'
};


gulp.task('style',function () {
    gulp.src(config.scssDir + '/*.sass')
        .pipe(sourcemaps.init())
        .pipe(sass())
      //  .on('error',sass.logError())
        .pipe(cssnano())
        .pipe(sourcemaps.write('maps'))
        .pipe(gulp.dest(config.cssDir))
});

gulp.task('watch',function ()
 {
    watch(config.scssDir + '/**/*.sass',function () {
        gulp.start('style');
    });
});

var elixir = require('laravel-elixir');

require('laravel-elixir-vue-2')

elixir(function(mix) {
    mix.less('styles.less');
    //mix.webpack('example.js'); // resources/assets/js
    mix.webpack('express.js'); // resources/assets/js
});