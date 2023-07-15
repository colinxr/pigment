<?php

namespace App\Providers;

use App\Services\GoogleCalendarService;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\GoogleCalendarInterface;
use App\Interfaces\GoogleApiServiceInterface;
use Illuminate\Contracts\Foundation\Application;

class GoogleCalendarServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(GoogleCalendarInterface::class, function (Application $app) {
            $apiService = $app->make(GoogleApiServiceInterface::class);
            return new GoogleCalendarService($apiService);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
