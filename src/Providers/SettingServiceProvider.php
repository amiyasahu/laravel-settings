<?php namespace Amiya\Setting\Providers;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Amiya\Setting\Foundation\JsonSetting;

class SettingServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot( Kernel $kernel )
    {
        $kernel->pushMiddleware( '\Amiya\Setting\Middleware\SaveSettingsMiddleware' );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes( [
            __DIR__ . '/../config/setting.php' => config_path( 'setting.php' ),
        ] );

        $this->app->bind( 'Amiya\Setting\Foundation\Setting', function ( $app ) {
            return new JsonSetting( $app, config( 'setting.path' ) . '/' . config( 'setting.filename' ) );
        } );

    }
}