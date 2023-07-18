<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Submission;
use App\Models\Appointment;
use Illuminate\Support\Carbon;
use App\Interfaces\GoogleCalendarInterface;
use App\Services\FakeGoogleApiService;
use App\Services\FakeGoogleCalendarService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    public $user;
    public $gCalService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['access_token' => []]);

        $client = Client::factory()->create(['user_id' => $this->user->id]);

        $submission = $client->submissions()->create(['user_id' => $this->user->id,]);

        $this->app->bind(GoogleApiServiceInterface::class, FakeGoogleApiService::class);

        $this->gCalService = new FakeGoogleCalendarService;
        $gCal = $this->gCalService;

        $this->app->bind(GoogleCalendarInterface::class, function () use ($gCal) {
            return $gCal;
        });
    }

    public function test_user_can_turn_submission_into_appointment()
    {
        $this->withoutExceptionHandling();
        $submission = $this->user->submissions->first();
        $rfc_time = preg_replace('/\.\d+/', 'Z', fake()->iso8601());
        $data = [
            'startDateTime' => '2023-05-26T12:08:00+0700', //$rfc_time, //fake()->dateTime()->format('Y-m-d H:i:s'),
            'duration' => 4,
            'price' => fake()->randomNumber(3),
            'deposit' => fake()->randomNumber(2),
            'name' => fake()->name(),
            'description' => fake()->text(),
            'client' => $submission->client->email,
        ];

        $this->actingAs($this->user);

        $response = $this->post("/v1/appointments?submission_id={$submission->id}", $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('appointments', [
            'user_id' => $this->user->id,
            'submission_id' => $submission->id,
            'startDateTime' => $data['startDateTime'],
        ]);

        $this->assertNotEmpty($this->gCalService->listEvents());

        $event = $this->gCalService->listEvents()[0];

        // event has the right details 
        $this->assertEquals($event['summary'], $data['name']);
    }

    public function test_user_can_update_their_appointment(): void
    {
        $appt = Appointment::factory()->create();
        $event = $this->gCalService->saveEvent($appt);
        $event->id = fake()->uuid();
        $appt->update(['event_id' => $event->id]);

        $this->assertNotEmpty($this->gCalService->listEvents());

        $newStartTime = fake()->dateTime()->format('Y-m-d\TH:i:sO');

        $this->actingAs($appt->user);
        $response = $this->put("/v1/appointments/{$appt->id}", [
            'name' => $appt->name,
            'price' => $appt->price,
            'description' => $appt->description,
            'startDateTime' => $newStartTime,
            'duration' => 4,
        ]);

        $response->assertStatus(204);

        $this->assertDatabaseHas('appointments', [
            'user_id' => $appt->user_id,
            'submission_id' => $appt->submission_id,
            'startDateTime' => $newStartTime,
        ]);

        $event = $this->gCalService->listEvents()[0];
        $this->assertEquals($newStartTime, $event->start['startDateTime']->format('Y-m-d\TH:i:sO'));
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

        $response = $this->get("/v1/appointments/");

        $content = json_decode($response->getContent());

        $response->assertStatus(200);
        $this->assertSame($content->data[0]->id, $appointments->first()->id);
        $this->assertEquals(2, count($content->data));
    }

    public function test_user_can_delete_appointment_and_events()
    {
        $appt = Appointment::factory()->create();

        $event = $this->gCalService->saveEvent($appt);
        $event->id = fake()->uuid();

        $appt->update(['event_id' => $event->id]);

        $this->assertNotEmpty($this->gCalService->listEvents());

        $this->actingAs($appt->user);
        $response = $this->delete("/v1/appointments/{$appt->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('appointments', ['id' => $appt->id]);
        $this->assertEmpty($this->assertNotEmpty($this->gCalService->listEvents()));
    }
}
