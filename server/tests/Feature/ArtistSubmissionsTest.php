<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Submission;
use App\Models\Appointment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArtistSubmissionsTest extends TestCase
{
    use RefreshDatabase;

    protected  function tearDown(): void
    {
        Storage::deleteDirectory('/public');
        Storage::makeDirectory('/public');
    }

    public function test_client_can_start_new_conversation_with_artist()
    {
        // need a new artist
        $user = User::factory()->create();

        $submission = [
            'email' => fake()->unique()->safeEmail(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->firstName(),
            'idea' => fake()->text(),
        ];

        $response = $this->post("/api/users/{$user->id}/submissions", $submission);

        $response->assertStatus(201);
        $response->assertJsonFragment(['message' => 'Your message has been successfully submitted.']);

        $this->assertDatabaseHas('clients', [
            'email' => $submission['email'],
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('submissions', ['idea' => $submission['idea']]);
    }

    public function test_client_can_submit_multiple_requests_to_same_artists()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $user->id,]);
        $first_submission = Submission::factory()->create([
            'client_id' => $client->id,
            'user_id' => $user->id,
        ]);

        $new_submission_data = [
            'email' => $client->email,
            'first_name' => $client->first_name,
            'last_name' => $client->last_name,
            'idea' => fake()->text(),
        ];

        $response = $this->post("/api/users/{$user->id}/submissions", $new_submission_data);

        $response->assertStatus(201);
        $response->assertJsonFragment(['message' => 'Your message has been successfully submitted.']);

        $this->assertDatabaseHas('clients', [
            'user_id' => $user->id,
            'email' => $client->email,
        ]);

        $this->assertEquals(1, Client::count());
        $this->assertCount(2, $user->submissions);
    }

    public function test_client_can_submit_to_multiple_artists()
    {
        $user = User::factory()->create();
        $user_b = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $user->id,]);
        $first_submission = Submission::factory()->create([
            'client_id' => $client->id,
            'user_id' => $user->id,
        ]);

        $new_submission_data = [
            'email' => $client->email,
            'first_name' => $client->first_name,
            'last_name' => $client->last_name,
            'idea' => fake()->text(),
        ];

        $response = $this->post("/api/users/{$user_b->id}/submissions", $new_submission_data);

        $response->assertStatus(201);
        $response->assertJsonFragment(['message' => 'Your message has been successfully submitted.']);

        $clients = Client::where(['email' => $client->email])->get();
        $this->assertCount(2, $clients);
        $this->assertCount(1, $user->submissions);
        $this->assertCount(1, $user_b->submissions);
    }

    public function test_artist_can_view_all_their_submissions()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $clients = Client::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $clients->each(function ($client) use ($user) {
            $submission = Submission::factory()->create([
                'client_id' => $client->id,
                'user_id' => $user->id,
            ]);
        });

        $this->actingAs($user);
        $response = $this->get("/api/submissions");

        $response->assertStatus(200);

        $submissions = json_decode($response->getContent())->submissions;
        $this->assertCount(3, $submissions->data);
    }

    public function test_api_returns_validation_errors_if_request_is_incomplete()
    {
        $user = User::factory()->create();

        $response = $this->post("/api/users/{$user->id}/submissions", [
            'email' => fake()->unique()->safeEmail(),
            'first_name' => fake()->firstName(),
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'message' => 'Validation errors occurred',
            'errors' => [
                'last_name' => ["The last name field is required."]
            ]
        ]);
    }

    public function test_client_can_submit_images()
    {
        $user = User::factory()->create();

        $data = [
            'email' => fake()->unique()->safeEmail(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->firstName(),
            'idea' => fake()->text(),
            'attachments' => [
                UploadedFile::fake()->image('test.jpg'),
                UploadedFile::fake()->image('test.png'),
            ],
        ];


        $response = $this->post("/api/users/{$user->id}/submissions", $data);
        $response->assertStatus(201);

        $this->assertDatabaseHas('submissions', [
            'idea' => $data['idea']
        ]);

        $submisison = $user->submissions()->first();
        $images = $submisison->getMedia('attachments');
        $this->assertCount(2, $images);
    }

    public function test_can_show_upcoming_and_past_appointments(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $user->id]);
        $submission = Submission::factory()->create(['user_id' => $user->id, 'client_id' => $client->id]);
        $upcoming = Appointment::factory()->count(2)->upcoming()->create(['submission_id' => $submission->id]);
        $past = Appointment::factory()->count(1)->past()->create(['submission_id' => $submission->id]);

        $this->assertCount(3, $submission->appointments);

        $this->actingAs($user);
        $response = $this->get("/api/submissions/{$submission->id}/appointments");
        $response->assertStatus(200);
    }
}
