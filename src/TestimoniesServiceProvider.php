<?php

namespace Faithgen\Testimonies;

use FaithGen\SDK\Traits\ConfigTrait;
use Faithgen\Testimonies\Services\TestimoniesService;
use Illuminate\Support\ServiceProvider;

class TestimoniesServiceProvider extends ServiceProvider
{
    use ConfigTrait;
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'testimonies');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'testimonies');

        $this->registerRoutes(__DIR__ . '/routes/testimonies.php', __DIR__ . '/routes/source.php');

        $this->setUpSourceFiles(function () {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

            $this->publishes([
                __DIR__ . '/../database/migrations/' => database_path('migrations')
            ], 'faithgen-testimonies-migrations');

            $this->publishes([
                __DIR__ . '/../storage/' => storage_path('app/public/testimonies')
            ], 'faithgen-testimonies-storage');
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('faithgen-testimonies.php'),
            ], 'faithgen-testimonies-config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/testimonies'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/testimonies'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/testimonies'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'faithgen-testimonies');

        // Register the main class to use with the facade
        $this->app->singleton('testimonies', function () {
            return new Testimonies;
        });

        //register tesmony services
        $this->app->singleton(TestimoniesService::class, TestimoniesService::class);
    }

    public function routeConfiguration(): array
    {
        return [
            'prefix' => config('faithgen-testimonies.prefix'),
            'namespace' => "Faithgen\Testimonies\Http\Controllers",
            'middleware' => config('faithgen-testimonies.middlewares'),
        ];
    }
}
