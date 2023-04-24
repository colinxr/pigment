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

    public $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $clients = Client::factory()->count(3)->create([
            'user_id' => $this->user->id,
        ]);
        $user = $this->user;

        $clients->each(function ($client) use ($user) {
            $submission = Submission::factory()->create([
                'client_id' => $client->id,
                'user_id' => $user->id,
            ]);

            $conversation = $submission->conversation()->create([
                'user_id' => $user->id,
                'client_id' => $client->id,
            ]);
        });
    }

    public function test_can_add_new_messages_in_a_conversation()
    {
        Mail::fake();
        $conversation = $this->user->conversations->first();

        $this->actingAs($this->user);
        $body = fake()->text();
        $response = $this->post("/api/conversations/{$conversation->id}/message", [
            'sender_id' => $this->user->id,
            'body' => $body,
        ]);

        $response->assertStatus(201);

        $messages = $conversation->messages;

        $this->assertCount(1, $messages);
        $this->assertEquals($body, $messages->first()->body);

        Mail::assertQueued(NewMessageAlert::class);
    }

    public function test_users_can_delete_a_conversation()
    {
        $this->actingAs($this->user);

        $this->assertDatabaseCount('conversations', 3);

        $conversation = $this->user->conversations->first();
        $submission = $conversation->submission;

        $conversation->newMessage($this->user, 'body text');

        $response = $this->delete("/api/conversations/{$conversation->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('conversations', [
            'id' => $conversation->id,
        ]);

        $this->assertDatabaseMissing('messages', [
            'conversation_id' => $conversation->id
        ]);

        $this->assertDatabaseMissing('submissions', [
            'id' => $submission->id,
        ]);
    }
}
