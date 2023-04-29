<?php

namespace Tests\Feature;

use Mockery;
use Google_Client;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;
use App\Services\GoogleApiService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CalendarTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        // $config = config('google-client');
        // $client = GoogleApiClient::client($config);
        // $service = new GoogleCalendarService($client);

        $user = User::factory()->create();
        // $user->createToken('api');

        $this->actingAs($user);
        $response = $this->get('/oauth/google/callback');

        $this->assertFalse(true);
    }

    public function test_can_get_auth_url()
    {
        $config = config('google-client');
        $service = new GoogleApiService($config);

        $auth_url = $service->client()->createAuthUrl();

        $this->assertNotNull($auth_url);
        $this->assertTrue(Str::contains(
            $auth_url,
            'https://accounts.google.com/o/oauth2/v2/auth'
        ));
    }

    // public function test_can_return_access_token()
    // {
    //     $user = User::factory()->create();

    //     // Mock the Google_Client class
    //     $clientMock = Mockery::mock(Google_Client::class);
    //     $clientMock->expects($this->once())
    //         ->method('fetchAccessTokenWithAuthCode')
    //         ->with(['access_token' => 'MY_ACCESS_TOKEN']);

    //     $this->actingAs($user);
    //     $auth_code = Str::random(8);
    //     $response = $this->post('/api/oauth/google/callback', [
    //         'code' => $auth_code,
    //     ]);

    //     $response->assertStatus(200);
    // }
}


// redirect to front-end 
// that page makes to JWT authenticated request
// to the back end /api/oauth/google/callback 
// that makes the request to the 