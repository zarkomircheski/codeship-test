const themeDirectory = 'wp-content/themes/grandfather/';

const gulp = require('gulp');
const sass = require('gulp-sass');
const bourbon = require('node-bourbon');
const cleanCSS = require('gulp-clean-css');
const autoprefixer = require('gulp-autoprefixer');
const concat = require('gulp-concat');
const gutil = require('gulp-util');
const cacheBust = require('./cacheBust.js');
const randomstring = require('randomstring');
const clean = require('gulp-clean');

var filename = 'app-' + randomstring.generate() + '.css';
var adminFilename = 'admin-'+ randomstring.generate() + '.css';
var ampFilename = 'amp-'+ randomstring.generate() + '.css';
var fawFilename = 'faw-'+ randomstring.generate() + '.css';

module.exports = {
  clean(cb) {
    gulp
      .src(`${themeDirectory}dist/css`, { read: false })
      .on('end', cb)
      .pipe(clean());
  },
  compile(cb) {
    gulp
      .src(`${themeDirectory}sass/new-style.scss`)
      .pipe(
        sass({
          includePaths: bourbon.includePaths
        })
      )
      .pipe(autoprefixer())
      .pipe(
        cacheBust({
          filename,
          type: 'css',
          variable: 'MAIN_STYLE'
        })
      )
      .on('error', gutil.log)
      .on('end', cb)
      .pipe(concat(filename))
      .pipe(cleanCSS({ compatibility: 'ie8' }))
      .pipe(gulp.dest(`${themeDirectory}dist/css`));
  },
  admin (cb) {
    gulp
      .src(`${themeDirectory}sass/admin/main.scss`)
      .pipe(
        sass({
          includePaths: bourbon.includePaths
        })
      )
      .pipe(autoprefixer())
      .pipe(
        cacheBust({
          'filename': adminFilename,
          type: 'css',
          variable: 'ADMIN_STYLE'
        })
      )
      .on('error', gutil.log)
      .on('end', cb)
      .pipe(concat(adminFilename))
      .pipe(cleanCSS({compatibility: 'ie8'}))
      .pipe(gulp.dest(`${themeDirectory}dist/css`))
  },
  amp (cb) {
    gulp
      .src(`${themeDirectory}sass/amp.scss`)
      .pipe(
        sass({
          includePaths: bourbon.includePaths
        })
      )
      .pipe(autoprefixer())
      .pipe(
        cacheBust({
          'filename': ampFilename,
          type: 'css',
          variable: 'AMP_STYLE',
          relative: true
        })
      )
      .on('error', gutil.log)
      .on('end', cb)
      .pipe(concat(ampFilename))
      .pipe(cleanCSS({compatibility: 'ie8'}))
      .pipe(gulp.dest(`${themeDirectory}dist/css`))
  },
  faw (cb) {
    gulp
      .src(`${themeDirectory}sass/faw.scss`)
      .pipe(
        sass({
          includePaths: bourbon.includePaths
        })
      )
      .pipe(autoprefixer())
      .pipe(
        cacheBust({
          'filename': fawFilename,
          type: 'css',
          variable: 'FAW_STYLE',
          relative: true
        })
      )
      .on('error', gutil.log)
      .on('end', cb)
      .pipe(concat(fawFilename))
      .pipe(cleanCSS({compatibility: 'ie8'}))
      .pipe(gulp.dest(`${themeDirectory}dist/css`))
  }
};
