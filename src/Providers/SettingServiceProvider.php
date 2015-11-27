<?php namespace Amiya\Setting\Providers;

use Illuminate\Support\ServiceProvider;
use Amiya\Setting\Foundation\JsonSetting;

class SettingServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

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
            return new JsonSetting( $this->app['files'] , config( 'setting.path' ) . '/' .config( 'setting.filename' ) );
        } );
    }
}