/* jshint node: true */
/* jshint es5: true */
'use strict';

// DEPENDENCIES =======================================================
var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var browserSync = require('browser-sync');
var pagespeed = require('psi');
var reload = browserSync.reload;


// FILE PATHS =========================================================
var theme = 'content/themes/<%= themeSlug %>';
var source = {

  styles: theme + '/source/styles/**/*.scss',
  scripts: theme + '/source/scripts/*.js',
  images: theme + '/source/images/*.{png,jpg,gif}',
  svgs: theme + '/source/images/*.svg',
  plugins: theme + '/source/vendor'

};
var assets = {

  styles: theme + '/assets/styles',
  scripts: theme + '/assets/scripts',
  images: theme + '/assets/images',
  vendor: theme + '/assets/vendor'

};
var plugins = [

  source.plugins + '/fastclick/lib/fastclick.js'

];
var vendor = [

  // source.plugins + '/idangerous-swiper/dist/js/swiper.jquery.min.js',
  // source.plugins + '/idangerous-swiper/dist/css/swiper.min.css',

];


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

  return gulp.src(theme + '/source/styles/*.scss')
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
    .pipe($.concat('main.js'))
    .pipe($.uglify())
    .pipe(gulp.dest(assets.scripts));

});

gulp.task('plugins', function () {

  return gulp.src(plugins)
    .pipe($.concat('plugins.js'))
    .pipe($.uglify())
    .pipe(gulp.dest(assets.scripts))
    .pipe($.size({title: 'plugins'}));

});

gulp.task('modernizr', function () {

  return gulp.src(source.scripts)
    .pipe($.modernizr({
      options: [
        'setClasses'
      ],
      tests: [
        'flexbox',
        'flexboxlegacy',
        'flexboxtweener'
      ],
      crawl: false
    }))
    .pipe($.uglify())
    .pipe(gulp.dest(assets.scripts));

});


// COPY ONE-OFF VENDOR SCRIPTS ========================================
gulp.task('vendor', function () {

  return gulp.src(vendor)
    .pipe(gulp.dest(assets.vendor))
    .pipe($.size({title: 'vendor'}));

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
    logPrefix: '☸',
    server: './'
  });

  gulp.watch([theme + '/**/*.php'], reload);
  gulp.watch([source.styles], ['styles', reload]);
  gulp.watch([source.scripts], ['scripts']);
  gulp.watch([source.images], ['images', reload]);
});
