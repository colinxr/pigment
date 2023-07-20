<?php

namespace App\Services;

use App\Models\User;
use App\Models\Client;
use App\Models\Message;
use App\Mail\NewMessageAlert;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class IncomingMessageService
{
  public function __construct()
  {
  }

  public function getUsername($inboundToEmail)
  {
    if (!preg_match('/^(.*?)@parse\.usepigment\.com/', $inboundToEmail, $matches)) return null;

    return $matches[1];
  }

  public function findUser($inboundToEmail)
  {
    $username = $this->getUsername($inboundToEmail);

    $user = User::where('username', $username)->first();

    return $user;
  }

  public function findClient(User $user, $fromEmail, $fromName)
  {
    $client_name = $this->getClientName($fromName);

    $client = Client::firstOrCreate(
      ['email' =>  $fromEmail],
      [
        'user_id' => $user->id,
        'first_name' => $client_name[0],
        'last_name' => $client_name[1],
      ]
    );

    return $client;
  }

  public function getClientName($inboundName)
  {
    if (!$inboundName)  return [' ', ' '];

    if (!preg_match('/^(.*?)(?=<)/', $inboundName, $matches)) return [' ', ' '];

    return explode(' ', $matches[1], 2);
  }

  public function extractReply($text)
  {
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

  public function storeMessage($payload)
  {
    $message = Message::create([
      'submission_id' => '',
      'sender_id' => '',
      'sender_type' => '',
      'body' => $this->extractReply($payload['text'])
    ]);
  }

  public function handleInboundMessage($payload)
  {
    try {
      $envelope = json_decode($payload['envelope']);

      $user = $this->findUser($envelope['to']);
      $client = $this->findClient($user, $envelope['from'], $payload['from']);

      $submission = $user->submissions()->create([
        'client_id' => $client->id,
      ]);

      $message = $submission->messages()->create([
        'sender_id' => $client->id,
        'sender_type' => get_class($client),
        'body' => $this->extractReply($payload['text']),
      ]);

      Mail::to($message->recipient())->queue(new NewMessageAlert($message));

      return $message;
    } catch (\Throwable $th) {
      Log::info($th);
      throw $th;
    }
  }
}
