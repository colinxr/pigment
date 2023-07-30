<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Carbon\CarbonPeriod;
use App\Models\Appointment;
use Illuminate\Support\Collection;

class CalendarAppointmentService
{
  private $user;
  private $calendar;

  public function __construct(User $user)
  {
    $this->user = $user;
    $this->calendar = $user->calendar;
  }


  public function getNextAvailableSlots(int $duration, $dayToQuery = null)
  {
    if (!$this->calendar->schedule) {
      throw new Exception('No work schedule set.', 1);
    }

    $slotsToFind = 3;
    $appointments = $this->appointmentsGroupedByDate($dayToQuery);

    $slots = $this->findAvailableSlots($appointments, $duration, $slotsToFind);

    $availableSlots = array_merge([], $slots);

    if (count($availableSlots) < $slotsToFind) {
      $dayToQuery = Carbon::createFromFormat('Y-m-d', $appointments->keys()->last());
      $remainingCount = $slotsToFind - count($availableSlots);

      $remaining = $this->fillRemainingSlots($dayToQuery, $duration, $remainingCount);
      $availableSlots = array_merge($availableSlots, $remaining);
    }

    return $availableSlots;
  }

  private function appointmentsGroupedByDate($dateTime = null, $offset = 0)
  {
    return $this->user->appointments()->upcoming($dateTime)
      ->offset($offset)
      ->limit(100)
      ->get()
      ->sortBy('startDateTime')
      ->groupBy(fn ($appt) => $appt->startDateTime->format('Y-m-d'))
      ->map(function ($grouped) {
        return [
          'totalTime' => $grouped->sum(function ($appt) {
            $start = $appt->startDateTime;
            $end = $appt->endDateTime;
            return $end->diffInMinutes($start) / 60;
          }),
          'appointments' => $grouped,
        ];
      });
  }

  private function findAvailableSlots(Collection $appointments, int $duration, int $slotsToFind = 3)
  {
    $firstDay = Carbon::now()->addDay(); // first day is always equal to tomorrow. 
    $lastDay = $appointments->isEmpty() ?
      $firstDay->copy()->addDays(5) :
      Carbon::createFromFormat('Y-m-d', $appointments->keys()->last()); // if no appointments, we'll add 5 days, otherwise, we'll take the last day in the appointment array. 

    // creates an array of every date, inbetween first day and late day. 
    $period = CarbonPeriod::create($firstDay, $lastDay->addDay());

    $availableSlots = [];
    // loops every each day in the period
    // and finds available slots for that day. 
    foreach ($period as $day) {
      $slots = $this->findSlotsForDay($day, $appointments, $duration);

      if (!$slots) continue;

      $availableSlots[] = $slots;

      if (count($availableSlots) === $slotsToFind) break;
    }

    return $availableSlots;
  }

  // 1. Does user work today? 
  // 2. Does $day have any appointments schedule? 
  // 3. Is there enough free time in the day to fit in the new appointment? 
  // 4. If there's only one appointment, can we schedule new appt before or after? 
  // 5. Find a big enough gap in the scheduled appts for the new appointment. 
  private function findSlotsForDay(Carbon $day, Collection $appointments, int $duration)
  {
    if (!$this->calendar->userWorksToday($day)) return [];

    $date = $day->format('Y-m-d'); // converts carbon object to '2023-07-21'
    if (!$appointments->has($date)) {
      return [
        'dateTime' => $this->calendar->getHoursOpening($day, true),
        'message' => 'Nothing scheduled this day',
      ];
    }

    // today won't work if the requested duration
    // is greater than the remaining hours left in the day. 
    $scheduledHours = $this->calendar->hoursFor($day);
    ['totalTime' => $totalTime, 'appointments' => $apptsForToday] = $appointments[$date];

    if ($duration > ($scheduledHours - $totalTime)) return [];

    if ($apptsForToday->count() === 1) {
      $hours = $this->findAppointmentRelativeToStoreHours($day, $apptsForToday->first());

      if ($duration <= $hours['from_opening']) {
        $hours = $this->calendar->getHoursOpening($day, true);
        $dateTime = $day->setTimeFromTimeString($hours)->toDateTimeString();

        return [
          'dateTime' => $dateTime,
          'message' => 'You\'re next appointment is at ' . $apptsForToday->first()->startDateTime->toTimeString()
        ];
      }

      if ($duration <= $hours['to_close']) {
        return [
          'dateTime' => $apptsForToday->first()->endDateTime->toDateTimeString(),
        ];
      }
    }

    return $this->getSlotBetweenAppointments($day, $apptsForToday, $duration);
  }

  // 1. loop over appointments on this day. 
  // 2. compare appt_1 end time to appt_2 start time
  // 3. if the gap between the two if big email to fit the duration,
  //    lets return an available slot. 
  private function getSlotBetweenAppointments(Carbon $day, Collection $appointments, int $duration)
  {
    $slots = [];
    foreach ($appointments as $key => $appt) {
      $firstTime = $appt->endDateTime;
      // if there's no next appointment this day,
      // let's set the $endTime to be equal to they end of the work day. 
      $secondTime = $key < $appointments->count() - 1 ?
        $appointments[$key + 1]->startDateTime :
        $this->calendar->getHoursClosing($day);

      $gap = $secondTime->diff($firstTime);

      if ($gap->h >= $duration) {
        $slots = array_merge($slots, ['dateTime' => $appt->endDateTime]);
      }
    }

    return $slots;
  }

  private function fillRemainingSlots(Carbon $dayToQuery, int $duration, int $remaining,)
  {
    $slots = [];
    $appointments = $this->appointmentsGroupedByDate($dayToQuery);
    $day = $dayToQuery->copy()->addDay();

    while (count($slots) < $remaining) {
      // skip today if the user doesn't work today 
      if (!$this->calendar->userWorksToday($day)) {
        $day->addDay();
        continue;
      }

      if (!$appointments->has($day->format('Y-m-d'))) {
        $slots[] = [
          'dateTime' => $this->calendar->getHoursOpening($day, true),
          'message' => 'Nothing scheduled this day',
        ];

        if (count($slots) === $remaining) break;
        $day->addDay();
        continue;
      }

      // find an available slot based on today's appointments
      $newSlots = $this->findAvailableSlots($appointments, $duration, $remaining);
      $slots = array_merge($slots, $newSlots);

      if (count($slots) === $remaining) break;

      $dayToQuery->addDay();
    }

    return $slots;
  }

  private function findAppointmentRelativeToStoreHours(Carbon $day, Appointment $appt)
  {
    $open = $this->calendar->getHoursOpening($day);
    $close = $this->calendar->getHoursOpening($day);

    $opening_time = $day->copy()->setTimeFromTimeString($open);
    $closing_time = $day->copy()->setTimeFromTimeString($close);


    $gapToFirstAppt = $appt->startDateTime->diff($opening_time);
    $gapToClose = $closing_time->diff($appt->endDateTime);

    return [
      'from_opening' => $gapToFirstAppt->h,
      'to_close' => $gapToClose->h,
    ];
  }
}
