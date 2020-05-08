const through = require('through2');
const replace = require('replace');

function cacheBust(options) {
  //regex to search for in functions/gulp-scripts.php
  const regex = new RegExp(
    `(define\\(\\'${options.variable}\\'([^;]+)\\);)`,
    'g'
  );

  // Replacement with new filename
  let replacement = '';
  if(options.relative === true) {
    replacement = `define('${options.variable}', '/dist/${options.type}/${options.filename}');`
  } else {
    replacement = `define('${options.variable}', fth_get_protocol_relative_template_directory_uri() . '/dist/${options.type}/${options.filename}');`;
  }

  return through.obj(function(file, enc, cb) {
    replace({
      regex,
      replacement,
      paths: ['wp-content/themes/grandfather/functions/gulp-scripts.php'],
      recursive: true,
      silent: true
    });

    cb(null, file);
  });
}

module.exports = cacheBust;
