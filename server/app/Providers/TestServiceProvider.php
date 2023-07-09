<?php

namespace App\Providers;

use App\Services\FakeGoogleApiService;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\GoogleCalendarInterface;
use App\Services\FakeGoogleCalendarService;
use App\Interfaces\GoogleApiServiceInterface;
use Illuminate\Contracts\Foundation\Application;

class TestServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->bind(GoogleApiServiceInterface::class, FakeGoogleApiService::class);

        $this->app->bind(GoogleCalendarInterface::class,  function (Application $app) {
            $apiService = $app->make(GoogleApiServiceInterface::class);
            return new FakeGoogleCalendarService($apiService);
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
