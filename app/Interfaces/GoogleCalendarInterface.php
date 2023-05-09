<?php

namespace App\Interfaces;

use App\Models\Appointment;

interface GoogleCalendarInterface
{
  public function createEventFromAppointment(Appointment $appointment);

  public function getClient();

  public function saveEvent(Appointment $appointment);

  public function getEvents();

  public function getService();
}
