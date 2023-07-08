<?php

namespace App\Services;

use App\Interfaces\GoogleApiServiceInterface;
use Google_Service_Calendar;

class FakeGoogleApiService implements GoogleApiServiceInterface
{
  private $config;

  public function __construct(array $config)
  {
    $this->config = $config;
  }

  public function client()
  {
    return self::createAuthenticatedGoogleClient($this->config);
  }

  public static function createAuthenticatedGoogleClient(array $config): object
  {
    return new \stdClass();
  }

  // public static function createAuthenticatedGoogleClient(array $config): ApiClient
  // {
  //   $authProfile = $config['default_auth_profile'];

  //   if ($authProfile === 'service_account') {
  //     return self::createServiceAccountClient($config['auth_profiles']['service_account']);
  //   }
  //   if ($authProfile === 'oauth') {
  //     return self::createOAuthClient($config['auth_profiles']['oauth']);
  //   }
  // }
}
