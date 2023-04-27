<?php

namespace App\Services;

use Google_Client;
use Google_Service_Calendar;

class GoogleCalendarService
{
  public $service;

  public function __construct(Google_Client $client)
  {
    $this->service = new Google_Service_Calendar($client);
  }

  public function getService()
  {
    return $this->service;
  }

  public function doSomethingUseful()
  {
    return 'Output from DemoOne';
  }
}
