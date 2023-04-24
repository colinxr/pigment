<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Submission;
use App\Mail\NewMessageAlert;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConversationTest extends TestCase
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

    public function test_can_add_new_messages_in_a_conversation()
    {
        Mail::fake();
        $conversation = $this->artist->conversations->first();

        $this->actingAs($this->artist);
        $body = fake()->text();
        $response = $this->post("/api/conversations/{$conversation->id}/message", [
            'sender_id' => $this->artist->id,
            'body' => $body,
        ]);

        $response->assertStatus(201);

        $messages = $conversation->messages;

        $this->assertCount(1, $messages);
        $this->assertEquals($body, $messages->first()->body);

        Mail::assertQueued(NewMessageAlert::class);
    }
}
