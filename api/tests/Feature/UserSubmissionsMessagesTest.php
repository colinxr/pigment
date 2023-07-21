<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Message;
use App\Models\Submission;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserSubmissionsMessagesTest extends TestCase
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
        });
    }

    public function test_can_view_messages_when_viewing_conversation()
    {
        $submission = $this->user->submissions->first();
        $messages_from_user = Message::factory()->count(3)->create([
            'submission_id' => $submission->id,
            'sender_id' => $this->user->id,
            'sender_type' => get_class($this->user),
        ]);

        $this->actingAs($this->user);
        $response = $this->get("/api/submissions/{$submission->id}");
        $response->assertStatus(200);


        $messages = json_decode($response->getContent())->messages;
        $this->assertCount(3, $messages);
    }

    public function test_submission_can_be_marked_as_read(): void
    {
        $submission = $this->user->submissions->first();

        $submission->update(['has_new_messages' => true]);

        $this->actingAs($this->user);
        $response = $this->get("/api/submissions/{$submission->id}/read");
        $response->assertStatus(204);

        $this->assertFalse(true);

        $this->assertDatabaseHas('submissions', [
            'id' => $submission->id,
            'has_new_messages' => false,
        ]);
    }

    // public function test_marked_unread_when_new_message_submitted()
    // {
    //     Mail::fake();
    //     $submission = $this->user->submissions->first();

    //     $this->actingAs($this->user);
    //     $body = fake()->text();

    //     $response = $this->post("/api/submissions/{$submission->id}/message", [
    //         'sender_id' => $this->user->id,
    //         'body' => $body,
    //     ]);

    //     $response->assertStatus(201);

    //     $this->assertDatabaseHas('submissions', [
    //         'id' => $submission->id,
    //         'has_new_messages' => true,
    //     ]);
    // }
}
