# Solid Collective

Collection of reusable stuff for Truepers.

You should be able to drop this folder in the root of your project and start cracking.

## Scaffolding

Copy files under `scaffolds` folder, they should serve as basic files which can be copied to your project.

`package.json` - Node modules packages, put it in your root project folder and run `npm install`
`gulp-build`   - Default gulp configuration
`assets`       - Assets structure, drop it in your root assets folder (vary between project type)

## Gulping

You will find required npm packages in `scaffolds/package.json`, move that to your root project folder and rename it to `package.json`, and run `npm install`.

Use the following code as your `gulpfile.js`.

```
var solid = require('./solid/assets/build/solid-gulp');

//
// Configure solid
// ----------------------
solid
    .configure()
    .from(__dirname + '/solid/assets/build/configs/laravel.js')
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
    .to('css/');

solid
    .css('vendor')
    .as('vendor.min.css')
    .message('Vendor CSS - Combined')
    .watch()
    .to('css/');

solid
    .uglify('app')
    .as('main.min.js')
    .message('Application Javascript compiled')
    .watch()
    .to('js/dist/');

solid
    .concat('vendor')
    .as('vendor.min.js')
    .message('Vendor javascript combined')
    .watch()
    .to('js/dist/');

//
// Group tasks
// -------------------------
solid.task('default', [
    'less.theme', 'css.vendor', 'uglify.app', 'concat.vendor'
]);
```

In your main `theme.less` file, you should import some default theme styles such as Bootstrap and Fontawesome.

```
@import "/solid/assets/less/boot";
@import "/solid/assets/less/vendor";
// Using awesome October components?
@import "/solid/assets/less/storm";
```

## Laravel Libraries

Autoload solid libraries to your composer file:

```
"require": {
	"codesleeve/laravel-stapler": "1.0.*",
    "anahkiasen/former": "4.0.*@dev",
    "maknz/slack": "^1.7"	
},
"autoload": {
	"psr-4": {
	    "Solid\\": "solid/src/"
	}
}
```

Load all providers and facades:

```
    ## Providers
        // Third party
        Codesleeve\LaravelStapler\Providers\L5ServiceProvider::class,
        Former\FormerServiceProvider::class,
        Maknz\Slack\SlackServiceProvider::class,

    ## Aliases
        // ThirdParty
        'Former'    => Former\Facades\Former::class,
        'Slack'     => Maknz\Slack\Facades\Slack::class,
```

Some short explanations on custom Solid Providers:

### Solid\Ajax\AjaxServiceProvider

Enable October AJAX framework to laravel. You will need to include October's javascript framework to use this.

There are few components here to include as described below.

#### Include Javascript framework

These are already available as part of Solid Gulp tasks (october and laravel version).

#### Extend from `Solid/Ajax/Controller`

Your controller should extend from Solid's controller to be able to response to Ajax request.

#### Add Exception Handler

Your project ExceptionHandler should extend from `Solid\Ajax\ExceptionHandler`, at:
`app/Exceptions/Handler.php`

> `'Ajax'      => Solid\Facades\Ajax::class,`

#### Add Middleware

Add  Solid's Ajax Middleware to intercept and correctly route Ajax requests.

> `\Solid\Ajax\AjaxMiddleware::class,`

### Solid\Flash\FlashServiceProvider

Enable quick and easy Flash-ing. Add this your main template, for ajaxed flash.
```
<div id="layout-flash-messages">
    @include('shared.flash-message')
</div>
```
with the content of flash-message:
```
<?php foreach (Flash::all() as $type => $message): ?>
    <p data-control="flash-message" class="flash-message <?= $type ?>" data-interval="5"><?= e($message) ?></p>
<?php endforeach ?>
```

> `'Flash'     => Solid\Facades\Flash::class,`

### Solid\Uploader\UploaderServiceProvider

Ported October uploader, to be used with Ajax

### Solid\Nav\NavServiceProvider

Easy navigation service provider, as middleware

> `'Nav'       => Solid\Facades\Nav::class,`
