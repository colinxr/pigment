<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Appointment;

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
    $apptsByDate = $this->appointsmentsGroupedByDate();

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

      $remaining = $this->fillRemainingSlots($remainingCount, $dayToQuery, $duration);
      $availableSlots = array_merge($availableSlots, $remaining);
    }

    return $availableSlots;
  }

  private function appointsmentsGroupedByDate($dateTime = null, $offset = 0)
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
    if ($appointments->isEmpty()) {
    }
    // date helpers 
    // create from string
    $firstDay = $appointments->isEmpty() ?
      Carbon::now()->addDay() :
      Carbon::createFromFormat('Y-m-d', $appointments->keys()->first());
    $lastDay = $appointments->isEmpty() ?
      $firstDay->copy()->addDays(5) :
      Carbon::createFromFormat('Y-m-d', $appointments->keys()->last());

    while ($firstDay <= $lastDay) {
      $slots = $this->findSlotsForToday($firstDay, $appointments, $duration);

      if (!$slots) {
        $firstDay->addDay();
        continue;
      }

      $availableSlots[] = $slots;

      if (count($availableSlots) === $slotsToFind) break;

      $firstDay->addDay();
    }

    return $availableSlots;
  }

  private function findSlotsForToday(Carbon $day, $appointments, int $duration)
  {
    // if today is not a day in the user's schedule, then today won't work. 
    if (!$this->calendar->userWorksToday($day)) return null;

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

    if ($duration > ($scheduledHours - $totalTime)) return null;

    // if there's only one appointment booked,
    // let's return the first availability. 

    // this doesn't make sense. 
    // doesn't accomodate an appointment booked at 3pm.
    // well need to compare the duration against the gaps between open and close hours...
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

  private function fillRemainingSlots($remaining, $dayToQuery, $duration,)
  {
    $slots = [];

    while (count($slots) <= $remaining) {
      $apptsByDate = $this->appointsmentsGroupedByDate($dayToQuery);

      if ($apptsByDate->isEmpty()) {
        $dayToQuery->addDay();

        if (!$this->calendar->userWorksToday($dayToQuery)) continue;

        // if today is a working day, but doesn't have any appointments scheduled
        // then lets return when the user starts work for the day.  
        $slots[] = [
          'dateTime' => $this->calendar->getHoursOpening($dayToQuery, true),
          'message' => 'Nothing scheduled this day',
        ];

        if (count($slots) === $remaining) break;
      } else {
        $newSlots = $this->findAvailableSlots($apptsByDate, $duration, $remaining);
        $slots = array_merge($slots, $newSlots);

        $dayToQuery = Carbon::createFromFormat('Y-m-d', $apptsByDate->keys()->last());
      }
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
