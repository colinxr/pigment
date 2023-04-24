<?php

namespace Tests\Feature;

use App\Models\Appointment;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Submission;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    public $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $client = Client::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $submission = $client->submissions()->create([
            'user_id' => $this->user->id,
        ]);
    }

    public function test_user_can_turn_submission_into_appointment()
    {
        $submission = $this->user->submissions->first();

        $data = [
            'date' => fake()->date(),
            'price' => fake()->randomNumber(3),
            'deposit' => fake()->randomNumber(2),
        ];

        $this->actingAs($this->user);

        $response = $this->post("/api/submissions/{$submission->id}/appointments", $data);

        $response->assertStatus(201)
            ->assertJson([
                'price' => $data['price'],
                'date' => $data['date'],
            ]);

        $this->assertDatabaseHas('appointments', [
            'user_id' => $this->user->id,
            'submission_id' => $submission->id,
            'date' => $data['date'],
        ]);
    }

    public function test_user_can_view_their_appointments()
    {
        $client = Client::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $submission = Submission::factory()->create([
            'user_id' => $this->user->id,
            'client_id' => $client->id,
        ]);

        $appointments = Appointment::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'submission_id' => $submission->id,
        ]);

        $this->actingAs($this->user);

        $response = $this->get("/api/appointments/");

        $response->assertStatus(200);

        $content = json_decode($response->getContent());

        $this->assertSame($content->data[0]->id, $appointments->first()->id);
    }
}
