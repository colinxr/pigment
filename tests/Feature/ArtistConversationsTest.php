<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Message;
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

            $conversation = $submission->conversation()->create([
                'user_id' => $artist->id,
                'client_id' => $client->id,
            ]);
        });
    }

    // public function test_artist_can_view_the_submission_details_in_a_conversation()
    // {
    //     $this->actingAs($this->artist);
    //     $conversation = $this->artist->conversations->first();

    //     $response = $this->get("/api/artist/conversations/{$conversation->id}");
    //     $response->assertStatus(200);

    //     $submission = json_decode($response->getContent())->submission;
    //     $this->assertEquals($submission->id, $this->artist->submissions->first()->id);
    // }

    // public function test_conversation_can_be_marked_as_read()
    // {
    //     $active_conversation = $this->artist->conversations->first();
    //     $this->actingAs($this->artist);

    //     $response = $this->get("/api/artist/conversations/{$active_conversation->id}");
    // }

    public function test_can_view_messages_when_viewing_conversation()
    {
        $conversation = $this->artist->conversations->first();
        $messages_from_artist = Message::factory()->count(3)->create([
            'conversation_id' => $conversation->id,
            'sender_id' => $this->artist->id,
            'sender_type' => get_class($this->artist),
        ]);

        $this->actingAs($this->artist);
        $response = $this->get("/api/conversations/{$conversation->id}");
        $response->assertStatus(200);


        $messages = json_decode($response->getContent())->messages;
        $this->assertCount(3, $messages);
    }
}
