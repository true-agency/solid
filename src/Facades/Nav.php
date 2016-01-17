<?php 

namespace Solid\Facades;


class Nav extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'nav';
    }
}
