<?php namespace Solid\Ajax;

use App;
use Illuminate\Support\Str;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;

/**
 * Register custom ajax routes
 */
class AjaxRoute
{

    protected $router = null;
    protected $app = null;

    protected $urlPrefix = 'ajax/';

    public function __construct(Router $router, Application $app)
    {
        $this->router = $router;
        $this->app = $app;
    }

    public function route($as, $action)
    {
        $this->router->post($this->urlPrefix . Str::slug($as), [
            'uses' => $action,
            'as'   => $as
        ]);
    }

    public function routes($array)
    {
        if (!is_array($array)) {
            throw new \Exception("Invalid type given", 1);
        }

        foreach ($array as $key => $value) {
            $this->route($key, $value);
        }
    }

}