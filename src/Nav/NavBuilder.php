<?php

namespace Solid\Nav;

class NavBuilder
{

    protected static $navs = [];
        
    public function __construct ()
    {
        static::$navs = [
            'default' => new NavInstance('default')
        ];
    }

    /**
     * Set active navigation location/context
     * For example if you have multiple navigations such as *sidebar* or *primary-nav*
     * 
     * @param string $key Unique key
     */
    public function set($key = 'default')
    {
        if (!array_key_exists($key, static::$navs)) {
            // Non-existent, create one
            static::$navs[$key] = new NavInstance($key);
        }

        return static::$navs[$key];
    }

    /**
     * Retrieve navigation instance by key
     * 
     * @param  string $key
     * @return Illuminate\Support\Fluent
     */
    public function get($key = 'default')
    {
        if (!isset(static::$navs[$key])) {
            return [];
        }

        return static::$navs[$key]->getItems();
    }

}
