var BaseTask = require('./base'),
    gulp     = require('gulp'),
    plumber       = require('gulp-plumber'),
    autoprefixer  = require('gulp-autoprefixer'),
    minifycss     = require('gulp-clean-css'),
    concat        = require('gulp-concat'),
    notify        = require('gulp-notify'),
    livereload    = require('gulp-livereload'),
    gulpif        = require('gulp-if'),

    lib      = require('./../lib');

var SolidCss = function (key, options, config) {
    this._prefix = 'css.'
    this._message = 'Css compiled'
    this._key = this._prefix + key
    this._config = config
    this._options = options
    this._as = null
    this._to = null
}

SolidCss.prototype = new BaseTask;
SolidCss.prototype.constructor = SolidCss

SolidCss.prototype.to = function(to) {
    var self = this
    this._to = to
    var isLocal = this._config.isLocal

    return gulp.task(self._key, function () {
        return gulp.src(lib.pathKey(self._key, self._config))
                .pipe(plumber({errorHandler: lib.onError}))
                .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 2'))
                .pipe(concat(self._as))
                .pipe(minifycss())
                .pipe(gulp.dest( self._config.asset_path + self._to ))
                .pipe(gulpif(isLocal, livereload()))
                .pipe(gulpif(isLocal, notify({ message: self._message })))
    });
}

module.exports = exports = SolidCss;
