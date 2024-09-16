<?php

namespace EomPlus\NovaIdle;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Http\Middleware\Authenticate;
use Laravel\Nova\Nova;
use EomPlus\NovaIdle\Http\Middleware\Authorize;
use Outl1ne\NovaTranslationsLoader\LoadsNovaTranslations;

class ToolServiceProvider extends ServiceProvider
{

    use LoadsNovaTranslations;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadTranslations(__DIR__ . '/../lang', 'nova-idle', true);

        if ($this->app->runningInConsole()) {
	    dump('runningInConsole');

            // Publish config
            $this->publishes([
                __DIR__ . '/../config/' => config_path(),
            ], 'config');

        }

        $this->app->booted(function () {
            $this->routes();
        });


        Nova::serving(function (ServingNova $event) {
           Nova::provideToScript([
              'novaIdle' => config('nova-idle')
           ]);
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/nova-idle.php',
            'nova-idle'
        );
    }
}
