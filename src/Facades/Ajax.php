<?php 

namespace Solid\Facades;


class Ajax extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'ajax.router';
    }
}
