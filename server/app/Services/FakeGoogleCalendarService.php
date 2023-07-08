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
    $this->events = collect();
  }

  public function getClient()
  {
    return [];
  }

  public function getService()
  {
    return [];
  }

  public function setToken(array|string $token)
  {
    return $token;
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

    $this->events->push($event);
    return $event;
  }

  public function updateEvent(string $event_id, Appointment $appt)
  {
    $event = $this->events->filter(fn ($item) => $item->id = $event_id)->first();

    $event->summary = $appt->name;
    $event->description = $appt->description;
    $event->start = ['startDateTime' => $appt->startDateTime];
    $event->end = ['endtDateTime' => $appt->endDateTime];

    $this->events = $this->events->map(function ($item) use ($event) {
      if ($item->id !== $event->id) return $item;

      return $event;
    });

    return $event;
  }

  public function deleteEvent(string $event_id)
  {
    $this->events->filter(function ($item) use ($event_id) {
      return $item->id !== $event_id;
    });
  }

  public function listEvents()
  {
    return $this->events;
  }

  public function getCalendarId()
  {
    return 'id';
  }

  public function watchCalendar(string $calendarId, string $notificationUrl)
  {
    return true;
  }

  public function watchEvent(string $calendarId, string $event_id)
  {
    return true;
  }
}
