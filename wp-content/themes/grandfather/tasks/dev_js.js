const themeDirectory = 'wp-content/themes/grandfather/';

const gulp = require('gulp');
const browserify = require('browserify');
const log = require('fancy-log');
const source = require('vinyl-source-stream');
const buffer = require('vinyl-buffer');
const concat = require('gulp-concat');
const sourcemaps = require('gulp-sourcemaps');

module.exports = {
  compile(cb) {
    browserify({
      entries: `${themeDirectory}js/app.js`,
      debug: true
    })
      .transform('babelify') // uses .babelrc for presets/transforms
      .bundle()
      .pipe(source('app.js'))
      .pipe(buffer())
      .on('error', err => log(err))
      .pipe(gulp.dest(`${themeDirectory}dev_assets/js`))
      .on('end', cb);
  },
  bundle(cb) {
    gulp
      .src([
        `${themeDirectory}js/vendor/vendor.js`,
        `${themeDirectory}dev_assets/js/app.js`,
        `${themeDirectory}js/scripts.js`
      ])
      .pipe(sourcemaps.init({ loadMaps: true }))
      .pipe(concat('app.js'))
      .on('error', (err) => {
        log(err);
        gulp.emit('error');
      })
      .pipe(sourcemaps.write('.'))
      .pipe(gulp.dest(`${themeDirectory}dev_assets/js`))
      .on('end', cb);
  }
};
