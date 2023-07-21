<?php

namespace App\Services;

use App\Exceptions\InboundMsgNoUserFound;
use App\Models\User;
use App\Models\Client;
use App\Models\Message;
use App\Models\Submission;
use App\Mail\NewMessageAlert;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class IncomingMessageService
{
  public function __construct()
  {
  }

  public function getUsername($toEmail)
  {
    if (!preg_match('/^(.*?)@mail\.usepigment\.com/', $toEmail, $matches)) return null;

    return $matches[1];
  }

  public function findUser($toEmail)
  {
    $username = $this->getUsername($toEmail);

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

    return $match ? trim($match) : $text;
  }

  public function getMessageId($headers)
  {
    if (!$headers) return '';

    if (!preg_match('/Message-ID: <(.*?)>/i', $headers, $matches)) return '';

    return $matches[1];
  }

  public function storeMessage(Submission $submission, Client $sender, $payload)
  {
    return $submission->newMessage(
      $sender,
      $this->extractReply($payload['text']),
      $this->getMessageId($payload['headers'])
    );
  }

  public function handleInboundMessage($payload)
  {
    try {
      $envelope = json_decode($payload['envelope']);

      $is_reply = str_contains($envelope->to[0], '@mail.'); // if to email is @mail.usepigment.com

      Log::info($payload['envelope']);

      $user = $this->findUser($envelope->to[0]);

      throw_if(!$user, new InboundMsgNoUserFound());

      $client = $this->findClient($user, $envelope->from, $payload['from']);

      $submission = $is_reply ?
        $user->submissions()->where('client_id', $client->id)->latest()->first() :
        $user->submissions()->create(['client_id' => $client->id,]);

      $message = $this->storeMessage($submission, $client, $payload);

      Mail::to($message->recipient())->queue(new NewMessageAlert($message));

      return $message;
    } catch (\Throwable $th) {
      Log::info($th);
      throw $th;
    }
  }
}
