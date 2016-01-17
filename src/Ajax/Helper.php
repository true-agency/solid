<?php namespace Solid\Ajax;

use App;
use Illuminate\Support\ServiceProvider;

/**
 * Register ajax service provider
 */
class Helper
{

    protected $request = null;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function isFrameworkAjax()
    {
        if (!$this->request->ajax() || $this->request->method() != 'POST') {
            return false;
        }

        if ($handler = $this->request->header('X_OCTOBER_REQUEST_HANDLER')) {
            return true;
        }

        return false;
    }

}