<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Client;
use App\Models\Submission;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create([
            'access_token' => [],
        ]);
        $submission = Submission::factory()->create([
            'user_id' => $user->id,
            'client_id' => Client::factory()->create(['user_id' => $user->id])
        ]);

        return [
            'name' => fake()->name(),
            'description' => fake()->text(),
            'startDateTime' => fake()->dateTime(),
            'endDateTime' => fake()->dateTime(),
            'price' => fake()->randomNumber(3),
            'user_id' => $user->id,
            'submission_id' => $submission->id,
        ];
    }

    /**
     * Define the "upcoming" state where startDateTime is in the future.
     *
     * @return AppointmentFactory
     */
    public function upcoming(): AppointmentFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'startDateTime' => Carbon::now()->addDays(7), // Example: set startDateTime 7 days from now
            ];
        });
    }

    /**
     * Define the "past" state where startDateTime is in the past.
     *
     * @return AppointmentFactory
     */
    public function past(): AppointmentFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'startDateTime' => Carbon::now()->subDays(7), // Example: set startDateTime 7 days ago
            ];
        });
    }
}
