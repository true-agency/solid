<?php namespace Solid\Facades;

class Flash extends \Illuminate\Support\Facades\Facade
{
    /**
     * Get the registered name of the component.
     * 
     * Resolves to:
     * - Solid\Flash\FlashBag
     * 
     * @return string
     */
    protected static function getFacadeAccessor() { return 'solid.flash'; }
}
