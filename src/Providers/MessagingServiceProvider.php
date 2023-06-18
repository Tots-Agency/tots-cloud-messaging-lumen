<?php

namespace Tots\CloudMessaging\Providers;

use Illuminate\Support\ServiceProvider;
use Tots\CloudMessaging\Services\MessagingService;

class MessagingServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register role singleton
        $this->app->singleton(MessagingService::class, function ($app) {
            return new MessagingService(config('messaging'));
        });
    }

    /**
     *
     * @return void
     */
    public function boot()
    {
        
    }
}
