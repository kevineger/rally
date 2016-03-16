<?php namespace ExternalAPIs\Google;

use Illuminate\Support\Facades\Facade;

class Google extends Facade {

    /**
     * Google is binding to 'google'.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'google';
    }
}