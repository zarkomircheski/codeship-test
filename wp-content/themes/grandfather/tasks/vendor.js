const themeDirectory = 'wp-content/themes/grandfather/';

const gulp = require('gulp');
const log = require('fancy-log');
const concat = require('gulp-concat');

module.exports = {
  build() {
    return gulp
      .src([
        // Make sure jquery is declared first
        `./${themeDirectory}js/vendor/jquery-3.4.1.min.js`,
        `./${themeDirectory}js/vendor/wp-embed.js`,
        `./${themeDirectory}js/vendor/themed-profiles.js`,
        `./${themeDirectory}js/vendor/arrayFrom.js`,
      ])
      .pipe(concat('vendor.js'))
      .pipe(gulp.dest(`${themeDirectory}js/vendor/`));
  }
};
