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
     * @group google
     */
    public function test_example(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $response = $this->get('/oauth/google/callback');

        $this->assertFalse(true);
    }

    /**
     * @group google
     */
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

    /**
     * @group google
     */
    public function test_can_return_access_token()
    {
        $user = User::factory()->create([
            'email' => 'colinxr@gmail.com'
        ]);

        $this->actingAs($user);

        $request_code = '4/0AbUR2VN5OiOOljIEgWElrbIh_Rgt5b6sSPNGvf4mdL9jHb_0gUTISvG9cJ2uKCx_kl0LyQ';

        $config = config('google-client');
        $service = new GoogleApiService($config);

        $token = $service->client()->fetchAccessTokenWithAuthCode($request_code);

        $this->assertNotNull($token);
    }

    /**
     * @group google
     */
    public function can_create_event_and_store_it_in_google_calendar(): void
    {
        // bind gcal interface to the real googlecalendar service 

        // get auth url from first test 
        // use auth url to log in to gcal with colinxr@gmail.com

        // get the authentication from URL 
        // use that to in the second test to get the access token 

        // store that token in the uesrs datdabase table

        // set that token in the api client 

        // call api to create an appointment 
        // assert response is 201 
        // 

        // use google calendar service to get events
        // find the event that matches the appointment we just created. 

    }
}


// redirect to front-end 
// that page makes to JWT authenticated request
// to the back end /api/oauth/google/callback 
// that makes the request to the 