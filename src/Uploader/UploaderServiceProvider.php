<?php namespace Solid\Uploader;

use Illuminate\Support\ServiceProvider;

class UploaderServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../../views/uploader', 'uploader');

        $this->publishes([
            __DIR__ . '/../../views' => base_path('resources/views/solid/uploader')
        ]);
    }

}
