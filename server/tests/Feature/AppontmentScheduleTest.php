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

    private function clearAppointmentsOnThisDate($user, $nextDay)
    {
        $appointments = $user->appointments()->where('startDateTime', 'LIKE',  "{$nextDay}%")->get();
        $appointments->each->delete();
    }

    public function test_can_get_next_appointment_window_where_day_has_nothing_scheduled(): void
    {
        $user = User::factory()->withCalendar()->create();
        $schedule = $this->schedule->toArray();

        $user->calendar->update(['schedule' => $schedule,]);

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

        $this->actingAs($user);
        $response = $this->get('/api/calendars/slots?duration=3');

        $response->assertStatus(200);

        $nextSlots = json_decode($response->getContent());

        $this->assertCount(4, $nextSlots->data);

        dump($nextSlots->data);

        // should have an appointment on friday. on next friday date 

        $this->assertFalse(true);
    }

    public function test_can_get_next_appointment_window_where_day_has_one_thing_scheduled(): void
    {
        $user = User::factory()->withCalendar()->create();
        $schedule = $this->schedule->toArray();

        $user->calendar->update(['schedule' => $schedule,]);

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

        $appt_a = Appointment::factory()->create([
            'user_id' => $user->id,
            'startDateTime' => $nextFriday->copy()->setTimeFromTimeString('10:00 am'),
            'endDateTime' => $nextFriday->copy()->setTimeFromTimeString('11:00 am'),
        ]);

        // $appt_b = Appointment::factory()->create([
        //     'user_id' => $user->id,
        //     'startDateTime' => $nextFriday->copy()->setTimeFromTimeString('01:00 pm'),
        //     'endDateTime' => $nextFriday->copy()->setTimeFromTimeString('05:00 pm'),
        // ]);

        $this->actingAs($user);
        $response = $this->get('/api/calendars/slots?duration=3');

        $response->assertStatus(200);

        $nextSlots = json_decode($response->getContent());

        $this->assertCount(4, $nextSlots->data);

        dump($nextSlots->data);

        $this->assertFalse(true);
    }

    public function test_can_get_next_appointment_window_where_there_is_gap_between_appointments(): void
    {
        $user = User::factory()->withCalendar()->create();
        $schedule = $this->schedule->toArray();

        $user->calendar->update(['schedule' => $schedule,]);

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

        $appt_a = Appointment::factory()->create([
            'user_id' => $user->id,
            'startDateTime' => $nextFriday->copy()->setTimeFromTimeString('10:00 am'),
            'endDateTime' => $nextFriday->copy()->setTimeFromTimeString('11:00 am'),
        ]);

        $appt_b = Appointment::factory()->create([
            'user_id' => $user->id,
            'startDateTime' => $nextFriday->copy()->setTimeFromTimeString('01:00 pm'),
            'endDateTime' => $nextFriday->copy()->setTimeFromTimeString('04:00 pm'),
        ]);

        $this->actingAs($user);
        $response = $this->get('/api/calendars/slots?duration=2');

        $response->assertStatus(200);

        $nextSlots = json_decode($response->getContent());

        $this->assertCount(4, $nextSlots->data);

        dump($nextSlots->data);

        $this->assertFalse(true);
    }
}
