<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArtistClientTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_user_can_update_their_clients_info_(): void
    {
        $user = User::factory()->create();

        $client = Client::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);
        $newEmail = fake()->email();
        $response = $this->put("/api/clients/{$client->email}", [
            'email' => $newEmail,
            'first_name' => $client->first_name,
            'last_name' => $client->last_name
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('clients', [
            'user_id' => $user->id,
            'email' => $newEmail,
            'first_name' => $client->first_name,
            'last_name' => $client->last_name
        ]);
    }
}
