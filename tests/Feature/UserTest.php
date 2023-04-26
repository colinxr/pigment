<?php

namespace Tests\Feature;

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
            ->assertJson([
                'last_name' => $data['last_name'],
                'email' => $data['email'],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
        ]);
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
}
