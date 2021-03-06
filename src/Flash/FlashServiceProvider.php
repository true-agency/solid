<?php namespace Solid\Flash;

use Illuminate\Support\ServiceProvider;

class FlashServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {
        $this->app['solid.flash'] = $this->app->share(function($app) {
            return new FlashBag;
        });
    }

    /**
     * Get the services provided by the provider.
     * @return array
     */
    public function provides()
    {
        return array('solid.flash');
    }
}
