## Ajax Framework

This framework is borrowed from OctoberCMS (http://octobercms.com) and modified to work with standard Laravel project. 

Credit goes to OctoberCMS team (http://octobercms.com)

### How to setup

Firstly, you'll need to set up Laravel to allow ajax registration
- Add `AjaxServiceProvider` to Providers
    ```
    'providers' => [

        Solid\Ajax\AjaxServiceProvider::class,

    ],
    ```
- Add Facade
    ```
    'aliases' => [
        'Ajax'      => Solid\Facades\Ajax::class,
    ]
    ```
- In your routes.php, use `Ajax::route` to register your ajax routes
    ```
    Ajax::route('ajax.logout', 'Auth\AuthController@onLogout');
    ```
    or use `Ajax::routes` to register multiple ajax routes
    ```
     Ajax::routes([
        'ajax.logout' => 'Auth\AuthController@onLogout',
        'ajax.login' => 'Auth\AuthController@onLogin',
    ]);
    ```
- In your middleware, add the following middleware to your stack:
    `\Solid\Ajax\AjaxMiddleware::class`
- Have your controller extend from `\Solid\Ajax\Controller` to allow Ajax based response
    
#### Include Javascript framework

These are already available as part of Solid Gulp tasks (october and laravel version).
Make sure to use October `framework.js` (customised) file to allow url as handler.

For CSRF token verification, ensure meta tag is added to your head:

`<meta name="csrf-token" content="{{ csrf_token() }}">`

### How to use

Everything should behave the same way as normal Laravel, on ajax-red routes, your controller can:
- return `view()` to return ajax-ed view content
- return array to return json output
- flash messages, which automatically show alert in front end (can return empty response)
