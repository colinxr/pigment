<?php

namespace App\Services;

use Google_Client;
use App\Models\Appointment;
use Illuminate\Support\Str;
use Google_Service_Calendar;
use Google\Service\Calendar\Event;
use Illuminate\Support\Facades\Log;
use Google\Service\Calendar\Channel;
use Illuminate\Support\Facades\Auth;
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

    // $this->client->setAccessToken(request()->user()->access_token);
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

  public function updateEvent(string $event_id, Appointment $appt)
  {
    $event = $this->service->events->get(Auth::user()->calendar_id, $event_id);

    $event->summary = $appt->name;
    $event->description = $appt->description;
    $event->start = ['dateTime' => $appt->startDateTime];
    $event->end = ['dateTime' => $appt->endDateTime];

    $response = $this->service->events->update(Auth::user()->calendar_id, $event_id, $event);

    // Handle the response
    if ($response->getStatusCode() == 204) {
      // Event deleted successfully
    } else {
      // Error deleting event
    }
  }

  public function deleteEvent(string $event_id)
  {
    $response = $this->service->events->delete(Auth::user()->calendar_id, $event_id);

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

  public function watchCalendar(string $calendarId, $notificationUrl)
  {
    // Set up the notification channel request
    $watchRequest = new Channel();
    $watchRequest->setId(Str::random(10));
    $watchRequest->setType('web_hook');
    $watchRequest->setAddress($notificationUrl);

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
      Log::info('no calendar id');
      $calendar = $this->getCalendarBySummary();

      if (!$calendar) {
        Log::info('need to create calendar');
        $calendar = $this->createCalendar();
      }

      Log::info('update the database');

      Log::info($calendar->id);

      Auth::user()->calendar_id = $calendar->id;
      Auth::user()->save();

      return $calendar->id;
    }

    return Auth::user()->calendar_id;
  }
}
