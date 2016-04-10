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

    /**
     * Register an ajax route
     *
     * @param  string $as     Route name
     * @param  string $action Controller@method signature
     * @param  array  $params Route parameters
     *
     * @return void
     */
    public function route($as, $action, $params = [])
    {
        $controllerName = explode('@', $action)[0];
        $actionName = explode('@', $action)[1];

        $url = $this->buildUrl($controllerName, $actionName, $params);

        $this->router->post($url, [
            'uses' => $action,
            'as'   => $as
        ]);
    }

    /**
     * Register routes configuration
     *
     * @param  $array
     *
     * @return void
     */
    public function routes($array)
    {
        if (!is_array($array)) {
            throw new \Exception("Invalid type given", 1);
        }

        foreach ($array as $routeName => $value) {
            $params = [];
            if (!is_array ($value)) {
                $uses = $value;
            } else {
                foreach ($value as $key => $routeConfig) {
                    if (is_numeric($key)) {
                        $uses = $routeConfig;
                    }
                    if ($key === 'params') {
                        $params = $routeConfig;
                    }
                }
            }
            $this->route($routeName, $uses, $params);
        }
    }

    /**
     * Build ajax url with additional parameters
     *
     * @param  string $action 
     * @param  array  $params 
     *
     * @return string
     */
    protected function buildUrl($controllerName, $action, $params)
    {
        $controllerName = str_replace('controller', '', strtolower(basename(str_replace('\\', '/', $controllerName))));

        $url = $this->urlPrefix . $controllerName . '/' . $action;
        foreach ($params as $param) {
            $url .= '/'. $param;
        }
        return $url;
    }

}