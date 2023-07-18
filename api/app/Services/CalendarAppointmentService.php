<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Appointment;
use Carbon\CarbonPeriod;

class CalendarAppointmentService
{
  public $user;
  private $calendar;
  // private $appointments;


  public function __construct(User $user)
  {
    $this->user = $user;
    $this->calendar = $user->calendar;
  }


  public function getNextAvailableSlots(int $duration, $dayToQuery = null)
  {
    $slotsToFind = 3;
    $apptsByDate = $this->appointmentsGroupedByDate();

    if (!$this->calendar->schedule) {
      return [
        'warning' => 'no calendar schedule set'
      ];
    }

    $slots = $this->findAvailableSlots($apptsByDate, $duration, $slotsToFind);

    $availableSlots = array_merge([], $slots);

    if (count($availableSlots) < $slotsToFind) {
      $dayToQuery = Carbon::createFromFormat('Y-m-d', $apptsByDate->keys()->last());
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

  private function findAvailableSlots($appointments, $duration, $slotsToFind = 3)
  {
    $availableSlots = [];
    $firstDay = Carbon::now()->addDay();
    $lastDay = $appointments->isEmpty() ?
      $firstDay->copy()->addDays(5) :
      Carbon::createFromFormat('Y-m-d', $appointments->keys()->last());

    $period = CarbonPeriod::create($firstDay, $lastDay);

    $count = 0;
    foreach ($period as $day) {
      $count++;

      $slots = $this->findSlotsForToday($day, $appointments, $duration);

      if (!$slots) continue;

      $availableSlots[] = $slots;

      if (count($availableSlots) === $slotsToFind) break;
    }

    return $availableSlots;
  }

  private function findSlotsForToday(Carbon $day, $appointments, int $duration)
  {

    // if today is not a day in the user's schedule, then today won't work. 
    if (!$this->calendar->userWorksToday($day)) return [];

    // if today is a working day, but doesn't have any appointments scheduled
    // then lets return when the user starts work for the day.  
    $date = $day->format('Y-m-d'); // date helpers -- convert to string

    if (!$appointments->has($date)) {
      return [
        'dateTime' => $this->calendar->getHoursOpening($day, true),
        'message' => 'Nothing scheduled this day',
      ];
    }

    ['totalTime' => $totalTime, 'appointments' => $apptsForToday] = $appointments[$date];
    // today won't work if
    // the total number of hours booked is greater 
    // than the duration of the new appointment
    $scheduledHours = $this->calendar->hoursFor($day);

    if ($duration > ($scheduledHours - $totalTime)) return [];

    // if there's only one appointment booked,
    // let's fund based on store hours
    if ($apptsForToday->count() === 1) {
      $slot = [];

      $hours = $this->findAppointmentRelativeToStoreHours($day, $apptsForToday->first());

      if ($duration <= $hours['from_opening']) {
        $hours = $this->calendar->getHoursOpening($day, true);
        $dateTime = $day->setTimeFromTimeString($hours)->toDateTimeString();

        $slot['dateTime'] = $dateTime;
        $slot['message'] = 'You\'re next appointment is at ' . $apptsForToday->first()->startDateTime->toTimeString();

        return $slot;
      }

      if ($duration <= $hours['to_close']) {
        $slot['dateTime'] = $apptsForToday->first()->endDateTime->toDateTimeString();

        return $slot;
      }

      return null;
    }

    $slots = [];

    foreach ($apptsForToday as $key => $appt) {
      $firstTime = $appt->endDateTime;
      // if there's no next appointment this day,
      // let's set the $endTime to be equal to they end of the work day. 
      $secondTime = $key < $apptsForToday->count() - 1 ?
        $apptsForToday[$key + 1]->startDateTime :
        $this->calendar->getHoursClosing($day);

      $gap = $secondTime->diff($firstTime);

      if ($gap->h >= $duration) {
        $slots = array_merge($slots, ['dateTime' => $appt->endDateTime,]);
      }
    }

    return $slots;
  }

  private function fillRemainingSlots($dayToQuery, $duration, $remaining,)
  {
    $slots = [];

    while (count($slots) < $remaining) {
      // skip today if the user doesn't work today 
      if (!$this->calendar->userWorksToday($dayToQuery)) {
        $dayToQuery->addDay();
        continue;
      }
      // if today is a working day,
      // then lets return when the user starts work for the day.  
      $slots[] = [
        'dateTime' => $this->calendar->getHoursOpening($dayToQuery, true),
        'message' => 'Nothing scheduled this day',
      ];

      if (count($slots) === $remaining) break;

      $dayToQuery->addDay();
      // $apptsByDate = $this->appointmentsGroupedByDate($dayToQuery);

      // if user has no appointments today
      // if ($apptsByDate->isEmpty()) {
      //   // skip today if the user doesn't work today 
      //   if (!$this->calendar->userWorksToday($dayToQuery)) {
      //     $dayToQuery->addDay();
      //     continue;
      //   }
      //   // if today is a working day,
      //   // then lets return when the user starts work for the day.  
      //   $slots[] = [
      //     'dateTime' => $this->calendar->getHoursOpening($dayToQuery, true),
      //     'message' => 'Nothing scheduled this day',
      //   ];

      //   if (count($slots) === $remaining) break;
      //   $dayToQuery->addDay();
      // } else {
      //   // get all the appointments for today
      //   $apptsByDate = $this->appointmentsGroupedByDate($dayToQuery);

      //   // find an available slot based on today's appointments
      //   $newSlots = $this->findAvailableSlots($apptsByDate, $duration, $remaining);
      //   $slots = array_merge($slots, $newSlots);

      //   if (count($slots) === $remaining) break;

      //   $dayToQuery->addDay();
      // }
    }

    return $slots;
  }

  public function findAppointmentRelativeToStoreHours(Carbon $day, Appointment $appt)
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
