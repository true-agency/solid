<?php namespace Solid\Ajax;

use App;
use Illuminate\Support\ServiceProvider;

/**
 * Register ajax service provider
 */
class AjaxServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ajax.router', function ($app)
        {
            return new AjaxRoute($app['router'], $app);
        });

        $this->app->singleton('ajax.helper', function ($app)
        {
            return new Helper($app['request']);
        });
    }

}