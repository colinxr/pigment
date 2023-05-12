<?php

namespace App\Interfaces;

use App\Models\Appointment;

interface GoogleCalendarInterface
{
  public function setToken(array|string $token);

  public function createEventFromAppointment(Appointment $appointment);

  public function getClient();

  public function saveEvent(Appointment $appointment);
  
  public function updateEvent(string $event_id, Appointment $appt);

  public function deleteEvent(string $event_id);

  public function listEvents();

  public function getService();

  public function getCalendarId();

  public function watchCalendar(string $calendarId, string $notificationUrl);

}
