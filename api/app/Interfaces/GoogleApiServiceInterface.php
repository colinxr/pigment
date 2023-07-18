<?php

namespace App\Interfaces;

interface GoogleApiServiceInterface
{
  public function client();

  public static function createAuthenticatedGoogleClient(array $config): object;
}
