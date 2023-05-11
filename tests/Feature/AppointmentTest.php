<?php

namespace Tests\Feature;

use App\Interfaces\GoogleCalendarInterface;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Submission;
use App\Models\Appointment;
use App\Services\FakeGoogleCalendarService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    public $user;
    public $gCalService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([ 'access_token' => [] ]);

        $client = Client::factory()->create([ 'user_id' => $this->user->id ]);

        $submission = $client->submissions()->create([ 'user_id' => $this->user->id, ]);

        $this->gCalService = new FakeGoogleCalendarService;
        $gCal = $this->gCalService;

        $this->app->bind(GoogleCalendarInterface::class, function () use ($gCal) {
            return $gCal;
        });
    }

    public function test_user_can_turn_submission_into_appointment()
    {
        $submission = $this->user->submissions->first();

        $data = [
            'start' => fake()->date(),
            'end' => fake()->date(),
            'price' => fake()->randomNumber(3),
            'deposit' => fake()->randomNumber(2),
            'name' => fake()->name(),
            'description' => fake()->text(),
        ];

        $this->actingAs($this->user);

        $response = $this->post("/api/submissions/{$submission->id}/appointments", $data);

        $response->assertStatus(201);
            // ->assertJson([
            //     'price' => $data['price'],
            //     'start' => $data['start'],
            // ]);

        $this->assertDatabaseHas('appointments', [
            'user_id' => $this->user->id,
            'submission_id' => $submission->id,
            'startDateTime' => $data['start'],
        ]);

        // make sure events is not empty
        // dump($this->gCalService->getEvents());
        $this->assertNotEmpty($this->gCalService->listEvents());

        $event = $this->gCalService->listEvents()[0];

        // event has the right details 
        $this->assertEquals($event['summary'], $data['name']);
    }

    public function test_user_can_view_their_appointments()
    {
        $this->withoutExceptionHandling();
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

        $content = json_decode($response->getContent());

        $response->assertStatus(200);
        $this->assertSame($content->data[0]->id, $appointments->first()->id);
        $this->assertEquals(2, count($content->data));
    }

    public function test_creating_appointment_can_create_an_event_in_GCal()
    {
        $submission = $this->user->submissions->first();

        $data = [
            'name' => fake()->text(),
            'description' => fake()->text(),
            'start' => fake()->date(),
            'end' => fake()->date(),
            'price' => fake()->randomNumber(3),
            'deposit' => fake()->randomNumber(2),
        ];
        // 
        $this->actingAs($this->user);

        $response = $this->post("/api/submissions/{$submission->id}/appointments", $data);

        // assert appointment exists
        $this->assertDatabaseHas('appointments', [
            'name' => $data['name'],
        ]);
    }
}
