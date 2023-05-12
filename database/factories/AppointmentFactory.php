<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Client;
use App\Models\Submission;
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
}
