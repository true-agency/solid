## Ajax Framework

This framework is borrowed from OctoberCMS and modified to work with standard Laravel project. Credit to OctoberCMS team. http://octobercms.com

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
    


And then, use the modified October `framework.js` file to allow url as handler.

### How to use

Use `response()->json()` to return data to ajax framework. If you're returning partial, follow OctoberCMS convention: `.selector => view('partial.name')->render()`.

For redirection, simply use `redirect()` as usual.

That should be it.
