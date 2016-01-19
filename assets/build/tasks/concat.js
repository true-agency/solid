var BaseTask = require('./base'),
    gulp     = require('gulp'),
    plumber       = require('gulp-plumber'),
    jshint        = require('gulp-jshint'),
    uglify        = require('gulp-uglify'),
    concat        = require('gulp-concat'),
    notify        = require('gulp-notify'),
    sourcemaps = require('gulp-sourcemaps'),
    gulpif     = require('gulp-if'),
    
    lib      = require('./../lib');

var SolidConcat = function (key, options, config) {
    this._prefix = 'concat.'
    this._message = 'Javascript combined'
    this._key = this._prefix + key
    this._config = config
    this._options = options
    this._as = null
    this._to = null
    this._uglifyOptions = {}
    this._useSourcemaps = false;
}

SolidConcat.prototype = new BaseTask;
SolidConcat.prototype.constructor = SolidConcat

SolidConcat.prototype.beautify = function () {
    this._uglifyOptions = {
        mangle: false,
        compress: false,
        output: {
            beautify: true
        }
    }
    return this
}

SolidConcat.prototype.sourcemaps = function () {
    this._useSourcemaps = true
    return this
}

SolidConcat.prototype.to = function(to) {
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
                .pipe(notify({ message: self._message }));
    });
}

module.exports = exports = SolidConcat;