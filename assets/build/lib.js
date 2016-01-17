/**
 * Gulp utility library
 */
var notify = require('gulp-notify');

var lib = {

    /**
     * Pipe notify on error, avoid crashing gulp
     *
     * @param  {[type]} err [description]
     *
     * @return {[type]}     [description]
     */
    onError: function(err) {
        notify.onError({
                    title:    "Error",
                    subtitle: "Abort, abort!",
                    message:  "Error: <%= error.message %>, File: <%= error.fileName %>",
                    sound:    "Beep"
                })(err);

        this.emit('end');
    },

    /**
     * Do dot notation value retrieval on given object
     * e.g. myobject = {
     *    foo: {
     *       bar: 'test'
     *    }
     * }
     * // _deep_find(myobject, 'foo.bar') === 'test'
     *
     * @param  string obj  
     * @param  string path 
     *
     * @return mixed
     */
    _deep_find: function (obj, path) {
        for (var i=0, path=path.split('.'), len=path.length; i<len; i++){
            if (!obj[path[i]]) 
                return null;
            obj = obj[path[i]];
        };
        return obj;
    },

    /**
     * Retrieve configuration value base on given {path}
     * When {base} is not supplied, will default to `asset_path`
     *
     * @param  string path
     * @param  string base
     *
     * @return mixed
     */
    path: function (path, base) {

        if (typeof base !== 'undefined') {
            return config[base] + path;
        }

        return config.asset_path + path;
    },

    /**
     * Retrieve configuration value by using dot (.) notation
     * on JSON Object
     *
     * @param  string pathKey
     *
     * @return mixed
     */
    pathKey: function (pathKey, config) {
        return lib._deep_find(config, pathKey);
    }

}; // ./ lib

module.exports = exports = lib;