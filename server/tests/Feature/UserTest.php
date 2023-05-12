<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_sign_up_for_service()
    {
        $data = array_merge(
            User::factory()->make()->toArray(),
            [
                'password' => 'secret',
                'password_confirmation' => 'secret'
            ]
        );

        $response = $this->post('api/users', $data);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'last_name' => $data['last_name'],
                'email' => $data['email'],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
        ]);

        $this->assertNotNull($response->getData()->token);
    }

    public function test_user_can_update_their_username()
    {
        $user = User::factory()->create();
        $new_username = fake()->userName();

        $this->actingAs($user);
        $response = $this->post('api/users/' . $user->id, [
            'username' => $new_username,
        ]);

        $response->assertStatus(204);
        $this->assertDatabaseHas('users', [
            'username' => $new_username,
        ]);
    }

    public function test_user_can_update_their_password()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $response = $this->post('api/users/' . $user->id, [
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);

        $response->assertStatus(204);
    }

    // public function test_can_store_access_tokens()
    // {
    //     $user = User::factory()->create();

    //     $token = [
    //         "access_token" => "ya29.a0AWY7CkmLp-cPmV1CaLAbaJaqbqcb7LEiFIrW4v8VqedBZMeWyP0E9GJaQVniun228Q_ApUpLtpNWpJR_ag-nZikMNlaSZmNUKao_sOyzxf5kiebf-xH0MWOrVUyak9tw-FxnSRgTKod-oOhQwcN8rRAO8uLFaCgYKAW8SARESFQG1tDrpR1aj4ywbYLDeZ2mv0Xty-A0163",
    //         "expires_in" => 3599,
    //         "refresh_token" => "1//060pXHwOMnXJHCgYIARAAGAYSNwF-L9IrG1oVzRum8NyCQoab0tHBpXjfR1x0YuuOgWznWrOOC6nXhueIjdKT5W_xHDGmCl_2em0",
    //         "scope" => "https://www.googleapis.com/auth/calendar",
    //         "token_type" => "Bearer",
    //         "created" => 1683234955,
    //     ];

    //     $user->access_token = $token);

    //     $this->assertNotNull($user->access_token);
    // }
}
