<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_token_is_expired(): void
    {
        $user = User::factory()->make([
            'access_token' => [
                'expires_in' => 3599,
                'created' => 1683234955,
            ],
        ]);

        $this->assertTrue($user->isTokenExpired());
    }

    public function test_token_is_valid()
    {
        $user = User::factory()->make(
            [
                'access_token' => [
                    "expires_in" => 3599,
                    "created" => Carbon::now()->timestamp,
                ],
            ]
        );

        $this->assertFalse($user->isTokenExpired());
    }
}
