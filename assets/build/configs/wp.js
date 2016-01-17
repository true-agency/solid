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
        theme: [
        ]  
    },
    concat : {
        vendor : [

            // Tooltip loaded first, because some other depends on this
            __dirname + "vendor/bootstrap/js/tooltip.js",

            // Exclude some bootstrap javascript we dont use
            '!' + __dirname + "vendor/bootstrap/js/affix.js",
            '!' + __dirname + "vendor/bootstrap/js/carousel.js",

            __dirname + "vendor/bootstrap/js/*.js",


            // Storm UI - OctoberCMS
            // @link http://octobercms.com
            __dirname + 'vendor/modernizr/js/modernizr.js',

            __dirname + "vendor/*/js/*.js",
        ]
    },
    uglify : {
        app : [

        ]
    },
    css : {
        vendor : [
            __dirname + "vendor/*/css/*.css",
        ],
    },

}; // ./ config

module.exports = exports = config;