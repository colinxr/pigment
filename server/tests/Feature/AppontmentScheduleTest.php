<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AppontmentScheduleTest extends TestCase
{

    use RefreshDatabase;

    public $schedule;
    public $carbonDays;

    protected function setUp(): void
    {
        parent::setUp();

        $this->schedule = collect([
            'monday' => $this->buildShopHours('9:00 am', '5:00 pm'),
            'tuesday' => $this->buildShopHours('9:00 am', '5:00 pm'),
            'wednesday' => $this->buildShopHours('9:00 am', '5:00 pm'),
            'thursday' => $this->buildShopHours('10:00 am', '7:00 pm'),
            'friday' => $this->buildShopHours('10:00 am', '5:00 pm')
        ]);

        $this->carbonDays = [
            'monday' => Carbon::MONDAY,
            'tuesday' => Carbon::TUESDAY,
            'wednesday' => Carbon::WEDNESDAY,
            'thursday' => Carbon::THURSDAY,
            'friday' => Carbon::FRIDAY,
            'saturday' => Carbon::SATURDAY,
            'sunday' => Carbon::SUNDAY,
        ];
    }

    private function setAppointmentsTotallyFull($user): void
    {
        $carbonDays = $this->carbonDays;

        $this->schedule->each(
            function ($hours, $day)
            use ($user, $carbonDays) {
                $nextDay = Carbon::now()->next($carbonDays[$day]);

                $appt = Appointment::factory()->create([
                    'user_id' => $user->id,
                    'startDateTime' => $nextDay->copy()->setTimeFromTimeString($hours['open']),
                    'endDateTime' => $nextDay->copy()->setTimeFromTimeString($hours['close']),
                ]);
            }
        );
    }

    private function clearAppointmentsOnThisDate($user, $nextFridayDate)
    {
        $friday_appointments = $user->appointments->where(['startDateTime', 'LIKE',  "$nextFridayDate%"]);
        $friday_appointments->each->delete();
    }

    public function test_can_get_next_appointment_window(): void
    {
        $user = User::factory()->withCalendar()->create();
        $schedule = $this->schedule->toArray();

        $user->calendar->update([
            'schedule' => $schedule,
        ]);

        $this->assertDatabaseHas('calendars', [
            'user_id' => $user->id,
            'schedule' => json_encode($schedule),
        ]);


        $this->setAppointmentsTotallyFull($user);

        $nextFriday = Carbon::now()->next(Carbon::FRIDAY);
        $nextFridayDate = $nextFriday->format('Y-m-d');
        $this->clearAppointmentsOnThisDate($user, $nextFridayDate);

        $this->assertDatabaseMissing('appointments', [
            'startDateTime' => $nextFridayDate . '%'
        ]);


        $this->assertNotNull($user->appointments);
    }
}
