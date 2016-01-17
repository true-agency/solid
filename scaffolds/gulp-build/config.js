/**
 * Gulp configuration, so Gulpfile.js can focus on running task
 * instead of configuring stuff.
 */

var asset_path = "public/assets/";

var config = {
    // Path to assets
    asset_path: asset_path,

    /**
     * Source paths, for each tasks
     */
    less : {
        theme: [
            asset_path + "less/theme.less"
        ]  
    },
    concat : {
        vendor : [
            asset_path + "vendor/*/js/*.js",
        ]
    },
    uglify : {
        app : [
            asset_path + "js/init.js",
            asset_path + "js/ui/*.js",
            asset_path + "js/partials/_*.js",
            asset_path + "js/routes.js"
        ]
    },
    css : {
        vendor : [
            asset_path + "vendor/*/css/*.css",
        ],
    },

    /**
     * Additional watch files,
     * by default all files in source paths are watched
     */
    watch: {
        // CSS
        less : {
            theme: [
                asset_path + 'less/**/*.less',
                asset_path + 'vendor/*/less/**/*.less'
            ]
        }
    },

}; // ./ config

module.exports = exports = config;