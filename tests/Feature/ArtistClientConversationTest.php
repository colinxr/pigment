<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArtistClientConversationTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_start_new_conversation_with_artist()
    {
        // need a new artist
        $artist = User::factory()->create();

        $submission = [
            'email' => fake()->unique()->safeEmail(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->firstName(),
            'idea' => fake()->text(),
        ];

        $response = $this->post("/api/artist/{$artist->id}/submissions", $submission);

        $response->assertStatus(201);
        $response->assertJsonFragment(['message' => 'Your message has been successfully submitted.']);

        // check the client exists 
        $client = Client::where([
            'email' => $submission['email'],
            'user_id' => $artist->id,
        ])->first();

        // check the submission exists


        $this->assertModelExists($client);
        $this->assertEquals($submission['email'], $client->email);

        // checck the conversations
        $this->assertCount(1, $artist->conversations);
        $this->assertEquals($client->id, $artist->conversations->first()->client_id);
    }

    public function test_client_can_submitt_multiple_requests_to_same_artists()
    {
        $artist = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $artist->id,]);
        $first_submission = Submission::factory()->create([
            'client_id' => $client->id,
            'user_id' => $artist->id,
        ]);
        $first_conversation = $artist->conversations()->create([
            'client_id' => $client->id,
            'submission_id' => $first_submission->id,
        ]);

        $new_submission_data = [
            'email' => $client->email,
            'first_name' => $client->first_name,
            'last_name' => $client->last_name,
            'idea' => fake()->text(),
        ];

        $response = $this->post("/api/artist/{$artist->id}/submissions", $new_submission_data);

        $response->assertStatus(201);
        $response->assertJsonFragment(['message' => 'Your message has been successfully submitted.']);

        $this->assertCount(1, $artist->clients);
        $this->assertCount(2, $artist->submissions);
        $this->assertCount(2, $artist->conversations);
    }
}
