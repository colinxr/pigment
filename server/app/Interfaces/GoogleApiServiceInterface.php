<?php

namespace App\Interfaces;

use Google_Client as ApiClient;

interface GoogleApiServiceInterface
{
  public function client();

  public static function createAuthenticatedGoogleClient(array $config): object;

  // protected static function createServiceAccountClient(array $authProfile): ApiClient;

  // protected static function createOAuthClient(array $authProfile): ApiClient;
}
