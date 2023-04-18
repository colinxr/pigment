<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Submission;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArtistConversationsTest extends TestCase
{
    use RefreshDatabase;
    public $artist;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artist = User::factory()->create();
        $clients = Client::factory()->count(3)->create([
            'user_id' => $this->artist->id,
        ]);
        $artist = $this->artist;

        $clients->each(function ($client) use ($artist) {
            $submission = Submission::factory()->create([
                'client_id' => $client->id,
                'user_id' => $artist->id,
            ]);

            $conversation = $artist->conversations()->create([
                'client_id' => $client->id,
                'submission_id' => $submission->id,
            ]);
        });
    }

    public function test_artist_can_view_all_their_conversations()
    {
        $this->actingAs($this->artist);
        $response = $this->get("/api/artist/conversations");

        $response->assertStatus(200);

        $conversations = json_decode($response->getContent())->conversations;
        $this->assertCount(3, $conversations);
    }

    public function test_artist_can_view_the_submission_details_in_a_conversation()
    {
        $this->actingAs($this->artist);
        $conversation = $this->artist->conversations->first();

        $response = $this->get("/api/artist/conversations/{$conversation->id}");
        $response->assertStatus(200);

        $submission = json_decode($response->getContent())->submission;
        $this->assertEquals($submission->id, $this->artist->submissions->first()->id);
    }

    // public function test_conversation_can_be_marked_as_read()
    // {
    //     $active_conversation = $this->artist->conversations->first();
    //     $this->actingAs($this->artist);

    //     $response = $this->get("/api/artist/conversations/{$active_conversation->id}");
    // }
}
