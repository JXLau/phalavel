<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Cache extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return app('cache');
    }

    public static function set($keyName = null, $content = null, $lifetime = null, $stopBuffer = true)
    {
    	return parent::save($keyName, $content, $lifetime, $stopBuffer);
    }
}