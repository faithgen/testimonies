<?php

namespace Faithgen\Testimonies;

use FaithGen\SDK\Services\ImageService;
use FaithGen\SDK\Traits\ConfigTrait;
use Faithgen\Testimonies\Models\Testimony;
use Faithgen\Testimonies\Observers\TestimonyObserver;
use Faithgen\Testimonies\Services\TestimoniesService;
use Illuminate\Support\ServiceProvider;

final class TestimoniesServiceProvider extends ServiceProvider
{
    use ConfigTrait;
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->registerRoutes(__DIR__ . '/../routes/testimonies.php', __DIR__ . '/../routes/source.php');

        $this->setUpSourceFiles(function () {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

            $this->publishes([
                __DIR__ . '/../database/migrations/' => database_path('migrations')
            ], 'faithgen-testimonies-migrations');
        });

        if(!config('faithgen-sdk.source'))
            $this->publishes([
                __DIR__ . '/../storage/' => storage_path('app/public/testimonies')
            ], 'faithgen-testimonies-storage');

        if ($this->app->runningInConsole())
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('faithgen-testimonies.php'),
            ], 'faithgen-testimonies-config');

        Testimony::observe(TestimonyObserver::class);
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'faithgen-testimonies');

        // Register the main class to use with the facade
        $this->app->singleton('testimonies', function () {
            return new Testimonies();
        });

        //register testimony services
        $this->app->singleton(TestimoniesService::class);
        $this->app->singleton(ImageService::class);
    }

    public function routeConfiguration(): array
    {
        return [
            'prefix' => config('faithgen-testimonies.prefix'),
            'middleware' => config('faithgen-testimonies.middlewares'),
        ];
    }
}
