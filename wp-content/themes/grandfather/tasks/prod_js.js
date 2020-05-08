const themeDirectory = 'wp-content/themes/grandfather/';

const gulp = require('gulp');
const browserify = require('browserify');
const log = require('fancy-log');
const source = require('vinyl-source-stream');
const buffer = require('vinyl-buffer');
const cacheBust = require('./cacheBust.js');
const randomstring = require('randomstring');
const concat = require('gulp-concat');
const terser = require('gulp-terser');
const clean = require('gulp-clean');

const filename = 'app-' + randomstring.generate() + '.js'

module.exports = {
  clean(cb) {
    return gulp
      .src(`${themeDirectory}dist/js`, { read: false })
      .pipe(clean());
  },
  compile(cb) {
    return browserify({
      entries: `${themeDirectory}js/app.js`,
      debug: false,
    })
      .transform('babelify') // uses .babelrc for presets/transforms
      .bundle()
      .pipe(source('app.js'))
      .pipe(buffer())
      .on('error', err => log(err))
      .pipe(gulp.dest(`${themeDirectory}dist/js`));
  },
  bundle(cb) {
    return gulp
      .src([
        `${themeDirectory}js/vendor/vendor.js`,
        `${themeDirectory}dist/js/app.js`,
        `${themeDirectory}js/scripts.js`
      ])
      .pipe(concat(filename))
      .pipe(cacheBust({
        filename,
        type: 'js',
        variable: 'MAIN_SCRIPT'
      }))
      .pipe(terser({
      })
        .on('error', (err) => {
          log(err);
          gulp.emit('error');
        }))
      .pipe(gulp.dest(`${themeDirectory}dist/js`));
  },
};
