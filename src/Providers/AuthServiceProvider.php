<?php

namespace Faithgen\Testimonies\Providers;

use Faithgen\Testimonies\Models\Testimony;
use Faithgen\Testimonies\Policies\TestimonyPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Testimony::class => TestimonyPolicy::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
