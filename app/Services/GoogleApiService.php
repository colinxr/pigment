<?php

namespace App\Services;

use Google_Service_Calendar;
use Google_Client as ApiClient;
use App\Exceptions\InvalidGCalConfiguration;

class GoogleApiService
{

  public $config;

  public function __construct(array $config)
  {
    $this->config = $config;
  }

  public function client()
  {
    return self::createAuthenticatedGoogleClient($this->config);
  }

  public static function createAuthenticatedGoogleClient(array $config): ApiClient
  {
    $authProfile = $config['default_auth_profile'];

    if ($authProfile === 'service_account') {
      return self::createServiceAccountClient($config['auth_profiles']['service_account']);
    }
    if ($authProfile === 'oauth') {
      return self::createOAuthClient($config['auth_profiles']['oauth']);
    }

    throw InvalidGCalConfiguration::invalidAuthenticationProfile($authProfile);
  }

  protected static function createServiceAccountClient(array $authProfile): ApiClient
  {
    $client = new ApiClient();

    $client->setScopes([
      Google_Service_Calendar::CALENDAR,
    ]);


    $client->setAuthConfig($authProfile['credentials_json']);

    return $client;
  }

  protected static function createOAuthClient(array $authProfile): ApiClient
  {
    $client = new ApiClient();

    $client->setScopes([
      Google_Service_Calendar::CALENDAR,
    ]);

    $client->setAuthConfig($authProfile['credentials_json']);
    $client->setRedirectUri(env('APP_URL') . '/oauth/google/callback');
    $client->setAccessType('offline');
    $client->setPrompt('consent');
    $client->setIncludeGrantedScopes(true);   // incremental auth

    return $client;
  }
}
