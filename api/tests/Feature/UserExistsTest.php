<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserExistsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_can_confirm_user_exists(): void
    {
        $user = User::factory()->create();

        $response = $this->get('/api/users/' . $user->username);

        $response->assertStatus(200);
    }

    public function test_returns_404_if_user_doesnt_exists(): void
    {
        $response = $this->get('/api/users/fake-user-name');

        $response->assertStatus(404);
    }
}
