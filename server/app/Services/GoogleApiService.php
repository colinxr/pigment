<?php

namespace App\Services;

use Google_Service_Calendar;
use Google_Client as ApiClient;
use Illuminate\Support\Facades\Log;
use App\Exceptions\InvalidGCalConfiguration;
use App\Interfaces\GoogleApiServiceInterface;

class GoogleApiService implements GoogleApiServiceInterface
{
  const SCOPES = Google_Service_Calendar::CALENDAR;

  private $config;

  public function __construct(array $config)
  {
    Log::info('testing');
    $this->config = $config;
  }

  public function client()
  {
    return self::createAuthenticatedGoogleClient($this->config);
  }

  public static function createAuthenticatedGoogleClient(array $config): object
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

    $client->setScopes([self::SCOPES]);
    $client->setAuthConfig($authProfile['credentials_json']);

    return $client;
  }

  protected static function createOAuthClient(array $authProfile): ApiClient
  {
    $client = new ApiClient();

    $client->setScopes([self::SCOPES]);
    $client->setAuthConfig($authProfile['credentials_json']);
    $client->setRedirectUri(config('app.web_url') . '/oauth/google/callback');
    $client->setAccessType('offline');
    $client->setPrompt('consent');
    $client->setIncludeGrantedScopes(true);

    return $client;
  }
}
