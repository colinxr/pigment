<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArtistSubmissionsTest extends TestCase
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
        $first_conversation = $first_submission->conversation()->create([
            'client_id' => $client->id,
            'user_id' => $artist->id,
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

    public function test_client_can_submit_to_multiple_artists()
    {
        $artist = User::factory()->create();
        $artist_b = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $artist->id,]);
        $first_submission = Submission::factory()->create([
            'client_id' => $client->id,
            'user_id' => $artist->id,
        ]);

        $new_submission_data = [
            'email' => $client->email,
            'first_name' => $client->first_name,
            'last_name' => $client->last_name,
            'idea' => fake()->text(),
        ];

        $response = $this->post("/api/artist/{$artist_b->id}/submissions", $new_submission_data);

        $response->assertStatus(201);
        $response->assertJsonFragment(['message' => 'Your message has been successfully submitted.']);

        $clients = Client::where(['email' => $client->email])->get();
        $this->assertCount(2, $clients);
        $this->assertCount(1, $artist->submissions);
        $this->assertCount(1, $artist_b->submissions);
    }

    public function test_artist_can_view_all_their_submissions()
    {
        $artist = User::factory()->create();
        $clients = Client::factory()->count(3)->create([
            'user_id' => $artist->id,
        ]);

        $clients->each(function ($client) use ($artist) {
            $submission = Submission::factory()->create([
                'client_id' => $client->id,
                'user_id' => $artist->id,
            ]);
        });

        $this->actingAs($artist);
        $response = $this->get("/api/submissions");

        $response->assertStatus(200);

        $submissions = json_decode($response->getContent())->submissions;
        $this->assertCount(3, $submissions);
    }
}
