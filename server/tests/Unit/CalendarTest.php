<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Calendar;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CalendarTest extends TestCase
{
    use RefreshDatabase;

    public $schedule;
    public $carbonDays;
    public $calendar;

    protected function setUp(): void
    {
        parent::setUp();

        $this->schedule = [
            'monday' => $this->buildShopHours('9:00 am', '5:00 pm'),
            'tuesday' => $this->buildShopHours('9:00 am', '5:00 pm'),
            'wednesday' => $this->buildShopHours('9:00 am', '5:00 pm'),
            'thursday' => $this->buildShopHours('10:00 am', '7:00 pm'),
            'friday' => $this->buildShopHours('10:00 am', '5:00 pm')
        ];

        dump(json_encode($this->schedule));

        $this->carbonDays = [
            'monday' => Carbon::MONDAY,
            'tuesday' => Carbon::TUESDAY,
            'wednesday' => Carbon::WEDNESDAY,
            'thursday' => Carbon::THURSDAY,
            'friday' => Carbon::FRIDAY,
            'saturday' => Carbon::SATURDAY,
            'sunday' => Carbon::SUNDAY,
        ];

        $this->calendar = new Calendar([
            'user_id' => User::factory()->make()->id,
            'schedule' => $this->schedule,
        ]);
    }

    /**
     * A basic unit test example.
     */

    public function test_can_tell_if_user_works_today_using_string()
    {
        $this->assertTrue($this->calendar->userWorksToday('monday'));
        $this->assertFalse($this->calendar->userWorksToday('Saturday'));
    }

    public function test_can_tell_if_user_works_today_using_carbon()
    {
        $carbonDate = Carbon::now();
        $dateNotInSchedule = Carbon::now()->next(Carbon::SATURDAY);

        $this->assertTrue($this->calendar->userWorksToday($carbonDate));
        $this->assertFalse($this->calendar->userWorksToday($dateNotInSchedule));
    }

    public function test_can_get_gap_to_opening_hours(): void
    {
        $date = Carbon::now()->next(Carbon::MONDAY);

        $open = $this->calendar->getHoursOpening($date); // 9:00 am
        $apptTime = $date->copy()->setTimeFromTimeString('01:00 pm');

        $gapToFirstAppt = $apptTime->diff($date->setTimeFromTimeString($open));

        $this->assertEquals(4, $gapToFirstAppt->h);
    }
}
