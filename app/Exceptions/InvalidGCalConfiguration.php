<?php

namespace App\Exceptions;

use Exception;

class InvalidGCalConfiguration extends Exception
{
    public static function credentialsJsonDoesNotExist(string $path)
    {
        return new static("Could not find a credentials file at `{$path}`.");
    }

    public static function credentialsTypeWrong($credentials)
    {
        return new static(sprintf('Credentials should be an array or the path of json file. "%s was given.', gettype($credentials)));
    }

    public static function invalidAuthenticationProfile($authProfile)
    {
        return new static("Authentication profile [{$authProfile}] does not match any of the supported authentication types.");
    }
}
