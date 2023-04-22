<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Submission;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    public $artist;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artist = User::factory()->create();

        $client = Client::factory()->create([
            'user_id' => $this->artist->id,
        ]);

        $submission = $client->submissions()->create([
            'user_id' => $this->artist->id,
        ]);
    }

    public function test_artist_can_turn_submission_into_appointment()
    {
        $submission = $this->artist->submissions->first();

        $data = [
            'date' => fake()->date(),
            'price' => fake()->randomNumber(3),
            'deposit' => fake()->randomNumber(2),
        ];

        $this->actingAs($this->artist);

        $response = $this->post("/api/submissions/{$submission->id}/appointments", $data);

        $response->assertStatus(201)
            ->assertJson([
                'price' => $data['price'],
                'date' => $data['date'],
            ]);

        $this->assertDatabaseHas('appointments', [
            'user_id' => $this->artist->id,
            'submission_id' => $submission->id,
            'date' => $data['date'],
        ]);
    }
}
