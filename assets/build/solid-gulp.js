var gulp          = require('gulp'),
    plumber       = require('gulp-plumber'),
	less          = require('gulp-less'),
	autoprefixer  = require('gulp-autoprefixer'),
	jshint        = require('gulp-jshint'),
	uglify        = require('gulp-uglify'),
	rename        = require('gulp-rename'),
	concat        = require('gulp-concat'),
	notify        = require('gulp-notify'),
	livereload    = require('gulp-livereload'),
	sourcemaps    = require('gulp-sourcemaps'),
	del           = require('del'),
	gulpif        = require('gulp-if'),
	minimist      = require('minimist'),
	argv          = require('yargs').argv,

    extend        = require('extend'),

    SolidLess     = require('./tasks/less'),
    SolidUglify   = require('./tasks/uglify'),
    SolidCss      = require('./tasks/css'),
    SolidConcat   = require('./tasks/concat'),
    lib           = require('./lib.js');

//cli options
var knownOptions = {
    string: 'env',
    default: { env: process.env.NODE_ENV || 'development' }
};

//slice the cli arguments
var options = minimist(process.argv.slice(2), knownOptions);

var asset_path = 'public/assets';

var configurable = {

    from: function (path) {
        var toMerge = require(path)

        // At the moment assuming we are at maximum two level deep
        for(var firstKey in toMerge) {
            if (!this.config[firstKey]) this.config[firstKey] = {}

            if (typeof toMerge[firstKey] !== 'object') {
                this.config[firstKey] = toMerge[firstKey]
            }
            else {
                for (var secondKey in toMerge[firstKey]) {
                    if (!this.config[firstKey][secondKey])
                        this.config[firstKey][secondKey] = toMerge[firstKey][secondKey]
                    else
                        this.config[firstKey][secondKey] = this.config[firstKey][secondKey].concat(toMerge[firstKey][secondKey])
                }
            }
        }
        return this
    },

    log: function () {
        console.log(this.config)
        return this
    },

    config: {
        isLocal: !argv.production
    }

}


/**
 * Main gulp command
 */
var solidGulp = {

    configure: function () {
        return configurable;
    },

    less: function (key) {
        return new SolidLess(key, options, configurable.config);
    },

    uglify: function (key) {
        return new SolidUglify(key, options, configurable.config);
    },

    css: function (key) {
        return new SolidCss(key, options, configurable.config);
    },

    concat: function (key) {
        return new SolidConcat(key, options, configurable.config);
    },

    task: function (name, tasks) {
        gulp.task(name, tasks);
    }

}

module.exports = exports = solidGulp;
