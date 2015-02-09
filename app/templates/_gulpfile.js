'use strict';

// DEPENDENCIES =======================================================
var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var del = require('del');
var runSequence = require('run-sequence');
var browserSync = require('browser-sync');
var pagespeed = require('psi');
var reload = browserSync.reload;


// FILE PATHS =========================================================
var source = {

  styles : 'content/themes/<%= _.slugify(siteName) %>/source/styles/**/*.scss',
  scripts : 'content/themes/<%= _.slugify(siteName) %>/source/scripts/*.js',
  images : 'content/themes/<%= _.slugify(siteName) %>/source/images/*.{png,jpg,gif}',
  svgs : 'content/themes/<%= _.slugify(siteName) %>/source/images/*.svg'

};
var assets = {

  styles : 'content/themes/<%= _.slugify(siteName) %>/assets/styles',
  scripts : 'content/themes/<%= _.slugify(siteName) %>/assets/scripts',
  images : 'content/themes/<%= _.slugify(siteName) %>/assets/images'

};


// AUTOPREFIXER CONFIG ================================================
var AUTOPREFIXER_BROWSERS = [
  'ie >= 8',
  'ie_mob >= 10',
  'ff >= 30',
  'chrome >= 34',
  'safari >= 7',
  'opera >= 23',
  'ios >= 7',
  'android >= 4.4',
  'bb >= 10'
];


// COMPILE STYLESHEETS ================================================
gulp.task('styles', function () {

  return gulp.src('source/styles/*.scss')
    .pipe($.changed('styles', {
      extension: '.scss'
    }))
    .pipe($.sass({
      precision: 10,
      onError: console.error.bind(console, 'SASS error:')
    }))
    .pipe($.autoprefixer({
      browsers: AUTOPREFIXER_BROWSERS
    }))
    .pipe($.csso())
    .pipe(gulp.dest(assets.styles))
    .pipe($.size({title: 'styles'}));

});


// LINT & CONCATENATE JS ==============================================
gulp.task('scripts', function () {

  return gulp.src(source.scripts)
    .pipe($.jshint())
    .pipe($.jshint.reporter('jshint-stylish'))
    .pipe(concat('scripts.js'))
    .pipe($.uglify())
    .pipe(gulp.dest(assets.scripts));

});


// OPTIMISE IMAGES ====================================================
gulp.task('images', function () {

  return gulp.src(source.images)
    .pipe($.changed(assets.images))
    .pipe($.imagemin({
      optimizationLevel: 3,
      progressive: true,
      interlaced: true
    }))
    .pipe(gulp.dest(assets.images));

});


// CREATE SVG SPRITE ==================================================
gulp.task('sprite', function () {

  return gulp.src(source.svgs)
  .pipe($.svgSprite({
    mode: {
      symbol: {
        dest: './',
        sprite: 'sprite.symbol.svg'
      }
    }
  }))
  .pipe(gulp.dest(assets.images));

});


// WATCH FOR CHANGES AND RELOAD =======================================
gulp.task('serve', ['styles'], function () {
  browserSync({
    notify: false,
    // Customize the BrowserSync console logging prefix
    logPrefix: '⎋',
    server: './'
  });

  gulp.watch(['**/*.html'], reload);
  gulp.watch([source.styles], ['styles', reload]);
  gulp.watch([source.scripts], ['scripts']);
  gulp.watch([source.images], ['images', reload]);
});
