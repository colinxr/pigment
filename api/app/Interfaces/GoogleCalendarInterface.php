<?php

namespace App\Interfaces;

use App\Models\Appointment;

interface GoogleCalendarInterface
{
  public function setToken(array|string $token);

  public function createEventFromAppointment(Appointment $appointment);

  public function getClient();

  public function saveEvent(Appointment $appt);

  public function updateEvent(Appointment $appt);

  public function deleteEvent(Appointment $appt);

  public function listEvents();

  public function getService();

  public function getCalendarId();

  public function watchEvent(string $calendarId, Appointment $appt);
}
