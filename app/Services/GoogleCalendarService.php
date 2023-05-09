<?php

namespace App\Services;

use App\Models\Appointment;
use Google_Service_Calendar;
use App\Services\GoogleApiService;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\Calendar;
use App\Interfaces\GoogleCalendarInterface;

class GoogleCalendarService implements GoogleCalendarInterface
{
  private $client;
  private $service;

  const CALENDAR_SUMMARY = 'DayPlanner';

  public function __construct(GoogleApiService $api)
  {
    $this->client = $api->client();
    $this->service = new Google_Service_Calendar($this->client);
  }

  public function getService()
  {
    return $this->service;
  }

  public function getClient()
  {
    return $this->client;
  }

  public function createEventFromAppointment(Appointment $appointment)
  {
    $event = new Event;

    $event->summary = $appointment->name;
    $event->description = $appointment->description;
    $event->start = ['dateTime' => $appointment->startDateTime];
    $event->end = ['dateTime' => $appointment->endDateTime];

    return $event;
  }

  public function saveEvent(Appointment $appointment)
  {
    $calendar = $this->checkCalendarExists();

    $event = $this->createEventFromAppointment($appointment);

    $this->service->events->insert($calendar->getId(), $event);

    return $event;
  }

  public function checkCalendarExists(string $calendarId = null)
  {
    $matchingCalendar = null;
    if ($calendarId) {
      $matchingCalendar = $this->getCalendarById($calendarId);
    }

    if (!$matchingCalendar) {
      $matchingCalendar = $this->getCalendarBySummary();
    }

    if (!$matchingCalendar) {
      $matchingCalendar = $this->createCalendar();
    }

    return $matchingCalendar;
  }

  public function getCalendarById(string $calendarId)
  {
    return $this->service->calendars->get($calendarId);
  }

  public function getCalendarBySummary(string $summary = self::CALENDAR_SUMMARY)
  {
    $matchingCalendar = null;
    $calendarList = $this->service->calendarList->listCalendarList();
    $calendars = $calendarList->getItems();

    foreach ($calendars as $cal) {
      if ($cal->getSummary() === $summary) {
        $matchingCalendar = $cal;
        break;
      }
    }

    return $matchingCalendar;
  }

  public function createCalendar()
  {
    $calendar = new Calendar;
    $calendar->setSummary(self::CALENDAR_SUMMARY);
    $calendar->setTimeZone('America/Los_Angeles');

    return $this->service->calendars->insert($calendar);
  }

  public function getEvents()
  {
  }
}
