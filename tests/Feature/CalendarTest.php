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


        dump($auth_url);

        $this->assertNotNull($auth_url);
        $this->assertTrue(Str::contains(
            $auth_url,
            'https://accounts.google.com/o/oauth2/v2/auth'
        ));
    }

    // public function test_can_return_access_token()
    // {
    //     $user = User::factory()->create([
    //         'email' => 'colinxr@gmail.com'
    //     ]);

    //     $this->actingAs($user);

    //     $request_code = '4/0AbUR2VN5OiOOljIEgWElrbIh_Rgt5b6sSPNGvf4mdL9jHb_0gUTISvG9cJ2uKCx_kl0LyQ';

    //     $config = config('google-client');
    //     $service = new GoogleApiService($config);

    //     $token = $service->client()->fetchAccessTokenWithAuthCode($request_code);

    //     dump($token);

    //     $this->assertFalse(true);
    // }`
}


// redirect to front-end 
// that page makes to JWT authenticated request
// to the back end /api/oauth/google/callback 
// that makes the request to the 