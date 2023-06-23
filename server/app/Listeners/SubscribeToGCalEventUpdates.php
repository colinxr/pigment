<?php

namespace App\Listeners;

use App\Events\AppointmentCreated;
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

        $this->gCalService->watchCalendar('primary', $event->appointment->event_id);
    }
}
