<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Submission;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArtistMessagesTest extends TestCase
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

    public function test_artist_can_view_all_their_messages()
    {
        $this->actingAs($this->artist);
        $response = $this->get("/api/artist/messages");

        $response->assertStatus(200);
        $this->assertCount(3, $this->artist->conversations);
    }
}
