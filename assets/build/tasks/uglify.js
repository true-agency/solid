var BaseTask   = require('./base'),
    gulp       = require('gulp'),
    plumber    = require('gulp-plumber'),
    jshint     = require('gulp-jshint'),
    concat     = require('gulp-concat'),
    uglify     = require('gulp-uglify'),
    notify     = require('gulp-notify'),
    livereload = require('gulp-livereload'),
    sourcemaps = require('gulp-sourcemaps'),
    gulpif     = require('gulp-if'),
    
    lib        = require('./../lib');

var SolidUglify = function (key, options, config) {
    this._prefix = 'uglify.'
    this._message = 'Javascipt compiled'
    this._key = this._prefix + key
    this._config = config
    this._options = options
    this._as = null
    this._to = null
    this._uglifyOptions = {}
    this._useSourcemaps = false;
}

SolidUglify.prototype = new BaseTask;
SolidUglify.prototype.constructor = SolidUglify

SolidUglify.prototype.beautify = function () {
    this._uglifyOptions = {
        mangle: false,
        compress: false,
        output: {
            beautify: true
        }
    }
    return this
}

SolidUglify.prototype.sourcemaps = function () {
    this._useSourcemaps = true
    return this
}

SolidUglify.prototype.to = function(to) {
    var self = this
    this._to = to

    return gulp.task(self._key, function () {
        return gulp.src(lib.pathKey(self._key, self._config))
                .pipe(plumber({errorHandler: lib.onError}))
                .pipe(gulpif(self._useSourcemaps, sourcemaps.init()))
                    .pipe(concat(self._as))
                    .pipe(uglify(self._uglifyOptions))
                .pipe(gulpif(self._useSourcemaps, sourcemaps.write()))
                .pipe(gulp.dest( self._config.asset_path + self._to ))
                .pipe(gulpif(self._options.env === 'development', livereload()))
                .pipe(notify({ message: self._message }))
    });
}

module.exports = exports = SolidUglify;