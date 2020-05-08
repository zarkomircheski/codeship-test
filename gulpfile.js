const themeDirectory = './wp-content/themes/grandfather/';

const gulp = require('gulp');
const run = require('run-sequence').use(gulp);

/* eslint-disable import/no-dynamic-require */
const devCss = require(`${themeDirectory}tasks/dev_css.js`);
const devJs = require(`${themeDirectory}tasks/dev_js.js`);
const prodCss = require(`${themeDirectory}tasks/prod_css.js`);
const prodJs = require(`${themeDirectory}tasks/prod_js.js`);
const vendor = require(`${themeDirectory}tasks/vendor.js`);
/* eslint-enable import/no-dynamic-require */

/**
 * Default tasks
 */
gulp.task('default', ['dev']);
gulp.task('dev', ['dev:js:build', 'dev:css:build', 'watch']);
gulp.task('prod', ['prod:js', 'prod:css']);
gulp.task('prod:js', ['prod:js:build']);
gulp.task('prod:css', ['prod:css:build']);

/**
 * Concat vendor files
 */
gulp.task('vendor', vendor.build);

/**
 * Watch task
 */
gulp.task('watch', () => {
  gulp.watch([`${themeDirectory}js/**/*.js`, 'js/**/**/*.js', `!${themeDirectory}js/vendor/*.js`], ['dev:js:build']);
  gulp.watch(`${themeDirectory}sass/**/*.scss`, ['dev:css:build']);
});

/**
 * Development tasks for CSS and JS
 */
gulp.task('dev:css:build', devCss.build);
gulp.task('dev:js:compile', devJs.compile);
gulp.task('dev:js:bundle', devJs.bundle);
gulp.task('dev:js:build', (cb) => {
  run(['vendor', 'dev:js:compile'], 'dev:js:bundle', cb);
});

/**
 * Production tasks for CSS and JS
 */
gulp.task('prod:css:clean', prodCss.clean);
gulp.task('prod:css:compile:admin', prodCss.admin);
gulp.task('prod:css:compile:amp', prodCss.amp);
gulp.task('prod:css:compile:faw', prodCss.faw);
gulp.task('prod:css:compile', prodCss.compile);
gulp.task('prod:css:build', (cb) => {
  run('prod:css:clean', 'prod:css:compile:admin', 'prod:css:compile:amp', 'prod:css:compile:faw', 'prod:css:compile', cb);
});
gulp.task('prod:js:clean', prodJs.clean);
gulp.task('prod:js:compile', prodJs.compile);
gulp.task('prod:js:bundle', prodJs.bundle);
gulp.task('prod:js:build', (cb) => {
  run(['prod:js:clean', 'vendor', 'prod:js:compile'], 'prod:js:bundle', cb);
});
