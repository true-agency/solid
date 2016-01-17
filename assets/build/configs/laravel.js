/**
 * Gulp configuration, so Gulpfile.js can focus on running task
 * instead of configuring stuff.
 */
var path = require('path')
var basepath = path.normalize(__dirname + '/../../')

var config = {

    /**
     * Source paths
     */
    less : {
       
    },
    concat : {
        vendor : [

            // Tooltip loaded first, because some other depends on this
            basepath + "vendor/bootstrap/js/tooltip.js",

            // Exclude some bootstrap javascript we dont use
            '!' + basepath + "vendor/bootstrap/js/affix.js",
            '!' + basepath + "vendor/bootstrap/js/carousel.js",

            basepath + "vendor/bootstrap/js/*.js",

            basepath + 'vendor/mustache/js/mustache.js',
            basepath + 'vendor/select2/js/select2.full.js',
            basepath + 'vendor/dropzone/js/dropzone.js',
            basepath + 'vendor/mousewheel/js/mousewheel.js',

            // AJAX framework
            // @link http://octobercms.com
            basepath + 'vendor/framework/js/framework.js',
            basepath + 'vendor/framework/js/framework.extras.js',

            // Storm UI - OctoberCMS
            // @link http://octobercms.com
            basepath + 'vendor/modernizr/js/modernizr.js',
            basepath + 'vendor/storm/js/foundation.baseclass.js',
            basepath + 'vendor/storm/js/foundation.controlutils.js',
            basepath + 'vendor/storm/js/flashmessage.js',
            basepath + 'vendor/storm/js/input.trigger.js',
            basepath + 'vendor/storm/js/input.hotkey.js',
            basepath + 'vendor/storm/js/loader.base.js',
            basepath + 'vendor/storm/js/checkbox.js',
            basepath + 'vendor/storm/js/select.js',
            basepath + 'vendor/storm/js/fileupload.js',
            basepath + 'vendor/storm/js/drag.scroll.js',
            basepath + 'vendor/storm/js/toolbar.js',

            basepath + "vendor/*/js/*.js",

        ]
    },
    uglify : {
        app : [
            basepath + "js/globals.js"
        ]
    },
    css : {
        vendor : [
            basepath + "vendor/*/css/*.css",
        ],
    }

}; // ./ config

module.exports = exports = config;