const themeDirectory = 'wp-content/themes/grandfather/';

const gulp = require('gulp');
const sass = require('gulp-sass');
const bourbon = require('node-bourbon');
const cleanCSS = require('gulp-clean-css');
const sourcemaps = require('gulp-sourcemaps');
const autoprefixer = require('gulp-autoprefixer');
const concat = require('gulp-concat');
const gutil = require('gulp-util');

module.exports = {
  build() {
    gulp
      .src(`${themeDirectory}sass/new-style.scss`)
      .pipe(sourcemaps.init())
      .pipe(
        sass({
          includePaths: bourbon.includePaths
        })
      )
      .pipe(autoprefixer())
      .on('error', gutil.log)
      .pipe(concat('app.css'))
      .pipe(sourcemaps.write('.'))
      .pipe(gulp.dest(`${themeDirectory}dev_assets/css`));
    gulp
      .src(`${themeDirectory}sass/admin/main.scss`)
      .pipe(sourcemaps.init())
      .pipe(
        sass({
          includePaths: bourbon.includePaths
        })
      )
      .pipe(autoprefixer())
      .on('error', gutil.log)
      .pipe(concat('admin.css'))
      .pipe(sourcemaps.write('.'))
      .pipe(gulp.dest(`${themeDirectory}dev_assets/css`));
    gulp
      .src(`${themeDirectory}sass/amp.scss`)
      .pipe(sourcemaps.init())
      .pipe(
        sass({
          includePaths: bourbon.includePaths
        })
      )
      .pipe(autoprefixer())
      .on('error', gutil.log)
      .pipe(concat('amp.css'))
      .pipe(sourcemaps.write('.'))
      .pipe(gulp.dest(`${themeDirectory}dev_assets/css`));
    gulp
      .src(`${themeDirectory}sass/faw.scss`)
      .pipe(sourcemaps.init())
      .pipe(
        sass({
          includePaths: bourbon.includePaths
        })
      )
      .pipe(autoprefixer())
      .on('error', gutil.log)
      .pipe(concat('faw.css'))
      .pipe(sourcemaps.write('.'))
      .pipe(gulp.dest(`${themeDirectory}dev_assets/css`));

  }
};
