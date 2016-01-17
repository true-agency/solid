var solidPath = __dirname + '/vendor/trueagency/solid/';
var solid = require(solidPath + 'assets/build/solid-gulp');

//
// Configure solid
// ----------------------
solid
    .configure()
    .from(solidPath + 'assets/build/configs/laravel.js')
    // Copy this from build/configs/sample.js
    .from(__dirname + '/gulp-build/config.js')

//
// Register tasks
// ----------------------
solid
    .less('theme')
    .as('main.min.css')
    .message('Theme - Less files completed')
    .watch()
    .to('css/')

solid
    .css('vendor')
    .as('vendor.min.css')
    .message('Vendor CSS - Combined')
    .watch()
    .to('css/')

solid
    .uglify('app')
    .as('main.min.js')
    // .beautify()
    // .sourcemaps()
    .message('Application Javascript compiled')
    .watch()
    .to('js/dist/')

solid
    .concat('vendor')
    .as('vendor.min.js')
    .message('Vendor javascript combined')
    .watch()
    .to('js/dist/')

//
// Group tasks
// -------------------------
solid.task('default', [
    'less.theme', 'css.vendor', 'uglify.app', 'concat.vendor'
])