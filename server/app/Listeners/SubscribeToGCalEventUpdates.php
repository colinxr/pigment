<?php

namespace App\Listeners;

use App\Events\AppointmentCreated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\InteractsWithQueue;
use App\Interfaces\GoogleCalendarInterface;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscribeToGCalEventUpdates
{
    /**
     * Create the event listener.
     */
    public function __construct(public GoogleCalendarInterface $gCalService)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AppointmentCreated $event): void
    {
        $this->gCalService->setToken($event->user->access_token);

        Log::info('calling from subcrine to gcal Listener');

        $watcher = $this->gCalService->watchEvent('primary', $event->appointment->event_id);

        Log::info(json_encode($watcher));
    }
}
