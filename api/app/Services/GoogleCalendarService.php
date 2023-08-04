<?php

namespace App\Services;

use Exception;
use App\Models\Appointment;
use Google_Service_Calendar;
use Google\Service\Calendar\Event;
use Illuminate\Support\Facades\Log;
use Google\Service\Calendar\Channel;
use Illuminate\Support\Facades\Auth;
use Google\Service\Calendar\Calendar;
use App\Interfaces\GoogleCalendarInterface;
use App\Interfaces\GoogleApiServiceInterface;

class GoogleCalendarService implements GoogleCalendarInterface
{
  private $client;
  private $service;

  const CALENDAR_SUMMARY = 'Pigment';

  public function __construct(GoogleApiServiceInterface $api)
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

  public function setToken(array|string $token)
  {
    return $this->client->setAccessToken($token);
  }

  public function createEventFromAppointment(Appointment $appt)
  {
    $event = new Event;

    $event->summary = $appt->name;
    $event->description = $appt->description;
    $event->start = ['dateTime' => $appt->startDateTime];
    $event->end = ['dateTime' => $appt->endDateTime];

    return $event;
  }

  public function saveEvent(Appointment $appt)
  {
    $calendar = $this->checkCalendarExists();

    $event = $this->createEventFromAppointment($appt);

    return $this->service->events->insert($calendar->getId(), $event);
  }

  public function updateEvent(Appointment $appt)
  {
    $cal_id = $this->getCalendarId();

    $event = $this->service->events->get($cal_id, $appt->event_id);

    Log::info(json_encode($event));

    if ($event) {
      $event->summary = $appt->name;
      $event->description = $appt->description;
      $event->start = ['dateTime' => $appt->startDateTime];
      $event->end = ['dateTime' => $appt->endDateTime];

      $event = $this->service->events->update(Auth::user()->calendar_id, $appt->event_id, $event);
    } else {
      // Error deleting event
      throw new Exception('Event was not updated in Google');
    }
  }

  public function deleteEvent(Appointment $appt)
  {
    $response = $this->service->events->delete(Auth::user()->calendar_id, $appt->event_id);

    // Handle the response
    if ($response->getStatusCode() == 204) {
      // Event deleted successfully
    } else {
      // Error deleting event
    }
  }

  public function checkCalendarExists(string $calendarId = null)
  {
    $matchingCalendar = null;

    // make sure the user's calendar ID actually exists in GCal
    if ($calendarId) {
      $matchingCalendar = $this->getCalendarById($calendarId);
    }

    // if it doesn't, lets check they have  calendar with the name 'DayPlanner'
    if (!$matchingCalendar) {
      $matchingCalendar = $this->getCalendarBySummary();
    }

    // if there's no matching calendar, let's create one. 
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

  public function watchEvent(string $calendarId, $event_id)
  {
    // Set up the notification channel request
    $watchRequest = new Channel();
    $watchRequest->setId($event_id);
    $watchRequest->setType('web_hook');
    $watchRequest->setAddress(url('/api/webhook/events?event_id=' . $event_id));

    $channel = $this->service->events->watch($calendarId, $watchRequest);

    // Store the channel information in your app's database
    // This is needed so you can later stop the channel when you want to stop watching for updates
    $channelExpiration = $channel->getExpiration();
    $channelToken = $channel->getResourceId();
    // Store the $channelExpiration and $channelToken in your database

    // Return the channel information to the caller
    return ['expiration' => $channelExpiration, 'token' => $channelToken];
  }

  public function listEvents()
  {
    $calendarId = $this->getCalendarId();

    return $this->service->events->listEvents($calendarId);
  }

  public function getCalendarId()
  {
    if (!Auth::user()->calendar_id) {
      $calendar = $this->getCalendarBySummary();

      if (!$calendar) {
        $calendar = $this->createCalendar();
      }

      Auth::user()->update([
        'calendar_id' => $calendar->id
      ]);

      return $calendar->id;
    }

    return Auth::user()->calendar_id;
  }
}
