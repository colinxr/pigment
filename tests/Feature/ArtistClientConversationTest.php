<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArtistClientConversationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        dd(config('app.env'));

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_client_can_start_new_conversation_with_artist()
    {
        // arrange
        // need a new artist
        $artist = User::factory()->create();

        $submission = [
            'email' => fake()->unique()->safeEmail(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->firstName(),
            'idea' => fake()->text(),
        ];
        // act
        $response = $this->post("/api/artist/{$artist->id}/submissions", $submission);

        // assert 
        $response->assertStatus(201);

        // check the submission exists

        // check the client exists 
        $client = Client::where([
            'email' => $submission['email'],
            'user_id' => $artist->id,
        ])->first();

        $this->assertModelExists($client);
        $this->assertEquals($submission['email'], $client->email);

        // checck the conversations
        $this->assertCount(1, $artist->conversations);
        $this->assertEquals($client->id, $artist->conversations->first()->client_id);
    }
}
