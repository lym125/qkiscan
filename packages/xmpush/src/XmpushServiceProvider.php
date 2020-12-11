<?php

namespace Qkiscan\Xmpush;

use Illuminate\Support\ServiceProvider;

class XmpushServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/xmpush.php', 'xmpush');

        Xmpush\Constants::setSecret($this->app['config']->get('xmpush.app_secret'));
        Xmpush\Constants::setPackage($this->app['config']->get('xmpush.package'));
        Xmpush\Constants::setBundleId($this->app['config']->get('xmpush.bundle_id'));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/xmpush.php' => config_path('xmpush.php'),
            ], 'qkiscan-xmpush');
        }
    }
}
