<?php

namespace App\Services;

use App\Models\User;
use App\Models\Client;

class IncomingMessageService
{
  public function __construct()
  {
  }

  public function getUsername($payload)
  {
    $toEmail = $payload['envelope']['to'];

    if (!preg_match('/^(.*?)@parse\.usepigment\.com/', $toEmail, $matches)) return null;

    return $matches[1];
  }

  public function findUser($payload)
  {
    $username = $this->getUsername($payload);

    $user = User::where('username', $username)->first();

    return $user;
  }

  public function findClient($payload)
  {
    $user = $this->findUser($payload);

    if (!$user) return null;

    $client_name = $this->getClientName($payload);
    dump($client_name);
    $client = Client::firstOrCreate(
      ['email' =>  $payload['envelope']['from']],
      [
        'user_id' => $user->id,
        'first_name' => $client_name[0],
        'last_name' => $client_name[1],
      ]
    );

    return $client;
  }

  public function getClientName($payload)
  {

    if (!preg_match('/^(.*?)(?=<)/', $payload['from'], $matches)) return [' ', ' '];

    return explode(' ', $matches[1], 2);
  }

  public function extractReply($payload)
  {
    $text = $payload['text'];

    $regexArr = [
      // (GMAIL, Apple Mail, thunderbird, Yahoo Mail) Add capture group to find everything before On ... wrote:
      '/^(.*?)(?=\s*On.*(\n)?wrote:)/i',

      // (Outlook, Window Mail) Add capture group to find everything before "From:"
      '/^(.*?)(?=\bFrom:\s)/i',

      // Add capture group to find everything before example@example.com wrote:
      '/^(.*?)(?=\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}\s+wrote:)/i',

      // Add capture group to find everything before -----Original Message-----
      '/^(.*?)(?=-+original\s+message-+\s*$)/i',

      // Add capture group to find everything before <example@example.com>
      '/^(.*?)(?=<[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}>\s*)/i',

    ];

    $match = null;

    foreach ($regexArr as $regex) {
      if (!preg_match($regex, $text, $matches)) continue;
      $match = $matches[1];
      break;
    }

    return trim($match);
  }
}
