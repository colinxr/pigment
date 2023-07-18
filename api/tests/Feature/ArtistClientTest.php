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

    public function test_user_can_update_their_clients_info_(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        $client = Client::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);
        $newEmail = fake()->email();
        $response = $this->put("/api/clients/{$client->id}", [
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

    public function test_user_can_fetch_their_client_lists(): void
    {
        $user = User::factory()->create();

        $clients = Client::factory()->count(5)->create(['user_id' => $user->id,]);

        $this->actingAs($user);
        $response = $this->get('/api/clients');

        $response->assertStatus(200);
        $data = json_decode($response->getContent());

        $this->assertCount(5, $data->data);
    }
}
