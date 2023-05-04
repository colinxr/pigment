<?php

namespace App\Services;

use Google_Service_Calendar;
use App\Models\Appointment;
use App\Services\GoogleApiService;
use Google\Service\Calendar\Event;
use App\Interfaces\GoogleCalendarInterface;

class GoogleCalendarService implements GoogleCalendarInterface
{
  private $client;
  private $service;

  public function __construct(GoogleApiService $api)
  {
    $this->client = $api->client();
    $this->service = new Google_Service_Calendar($this->client);
  }

  public function getService()
  {
    return $this->service;
  }

  public function createEventFromAppointment(Appointment $appointment)
  {
    $event = new Event;

    $event->name = $appointment->name;
    $event->description = $appointment->description;
    $event->startDateTime = $appointment->startDateTime;
    $event->endDateTime = $appointment->endDateTime;

    return $event;
  }

  public function saveEvent(Appointment $appointment)
  {
    $event = $this->createEventFromAppointment($appointment);

    $this->service->getCalendar('primary')->insertEvent($event);

    return $event;
  }

  public function getEvents()
  {
  }
}
