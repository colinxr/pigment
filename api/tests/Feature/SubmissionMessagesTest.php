<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Submission;
use App\Mail\NewMessageAlert;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubmissionMessagesTest extends TestCase
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

    protected  function tearDown(): void
    {
        // Storage::deleteDirectory('/media-library');
        Storage::deleteDirectory('/public');
        Storage::makeDirectory('/public');
    }

    public function test_can_add_new_messages_in_a_conversation()
    {
        Mail::fake();
        $submission = $this->user->submissions->first();

        $this->actingAs($this->user);
        $body = fake()->text();
        $response = $this->post("/v1/submissions/{$submission->id}/message", [
            'sender_id' => $this->user->id,
            'body' => $body,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('messages', [
            'body' => $body,
            'submission_id' => $submission->id,
        ]);

        Mail::assertQueued(NewMessageAlert::class);
    }

    public function test_messages_can_have_attachments()
    {
        Mail::fake();
        $submission = $this->user->submissions->first();

        $this->actingAs($this->user);

        $body = fake()->text();
        $response = $this->post("/v1/submissions/{$submission->id}/message", [
            'sender_id' => $this->user->id,
            'body' => $body,
            'attachments' => [
                UploadedFile::fake()->image('test.jpg'),
                UploadedFile::fake()->image('test.png'),
            ],
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('messages', [
            'body' => $body,
            'submission_id' => $submission->id,
        ]);

        $message = $submission->messages->first();
        $images = $message->getMedia('attachments');
        $this->assertCount(2, $images);

        Mail::assertQueued(NewMessageAlert::class, function (NewMessageAlert $mail) {
            return true && count($mail->attachments()) === 2;
        });
    }

    public function test_users_can_delete_a_conversation()
    {
        $this->actingAs($this->user);

        $this->assertDatabaseCount('submissions', 3);

        $submission = $this->user->submissions->first();

        $submission->newMessage($this->user, 'body text');

        $response = $this->delete("/v1/submissions/{$submission->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('messages', [
            'conversation_id' => $submission->id
        ]);

        $this->assertDatabaseMissing('submissions', [
            'id' => $submission->id,
        ]);
    }
}
