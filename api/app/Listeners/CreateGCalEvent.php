<?php

namespace App\Listeners;

use App\Events\AppointmentCreated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\InteractsWithQueue;
use App\Interfaces\GoogleCalendarInterface;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateGCalEvent
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

        $cal_event = $this->gCalService->saveEvent($event->appointment);

        $event->appointment->update(['event_id' => $cal_event->getId()]);
    }
}
