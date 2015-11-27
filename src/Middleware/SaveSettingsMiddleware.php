<?php namespace Amiya\Setting\Middleware;

use Amiya\Setting\Foundation\Setting;
use Closure;

class SaveSettingsMiddleware
{
    protected $setting;

    function __construct( Setting $setting )
    {
        $this->setting = $setting;
    }

    public function handle( $request, Closure $next )
    {
        return $next( $request );
    }

    public function terminate( $request, $response )
    {
        $this->setting->save();
    }
}