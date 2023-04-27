<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\GoogleApiClient;
use App\Services\GoogleApiService;
use App\Services\GoogleCalendarService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CalendarTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        // $config = config('google-client');
        // $client = GoogleApiClient::client($config);
        // $service = new GoogleCalendarService($client);


        $this->assertFalse(true);
    }

    public function test_can_get_auth_url()
    {
        $config = config('google-client');
        $service = new GoogleApiService($config);

        $auth_url = $service->client()->createAuthUrl();

        dump($auth_url);
        $this->assertNotNull($auth_url);
    }
}
