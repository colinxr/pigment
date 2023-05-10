<?php

namespace App\Services;

use App\Models\Appointment;
use Google\Service\Calendar\Event;
use App\Interfaces\GoogleCalendarInterface;

class FakeGoogleCalendarService implements GoogleCalendarInterface
{
  public $events;

  public function __construct()
  {
    $this->events = [];
  }

  public function getClient()
  {
    return [];
  }

  public function getService()
  {
    return [];
  }

  public function createEventFromAppointment(Appointment $appointment)
  {
    $event = new Event;

    $event->summary = $appointment->name;
    $event->description = $appointment->description;
    $event->startDateTime = $appointment->startDateTime;
    $event->endDateTime = $appointment->endDateTime;

    return $event;
  }

  public function saveEvent(Appointment $appointment)
  {
    $event = $this->createEventFromAppointment($appointment);

    $this->events[] = $event;
    return $event;
  }

  public function getEvents()
  {
    return $this->events;
  }

  public function getCalendarId()
  {
    return 'id';
  }
}
