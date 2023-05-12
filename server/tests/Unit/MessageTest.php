<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_message_can_generate_the_recipient()
    {
        $user = User::factory()->create();

        $client = Client::factory()->create([
            'user_id' => $user->id,
        ]);

        $submission = Submission::factory()->create([
            'client_id' => $client->id,
            'user_id' => $user->id,
        ]);

        $conversation = $submission->conversation()->create([
            'user_id' => $user->id,
            'client_id' => $client->id,
        ]);

        $message_from_user = $conversation->messages()->create([
            'sender_id' => $user->id,
            'sender_type' => get_class($user),
            'body' => fake()->text(),
        ]);

        $this->assertEquals($client->email, $message_from_user->recipient());
    }
}
