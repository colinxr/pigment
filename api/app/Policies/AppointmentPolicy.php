<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Submission;
use App\Models\Appointment;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppointmentPolicy
{
  use HandlesAuthorization;

  public function view(User $user, Appointment $appointment)
  {
    return $user->id === $appointment->user_id
      ? Response::allow()
      : response()->json([
        'error' => 'You don\'t have permission to view this client.'
      ], 400);
  }

  public function viewSubmission(User $user, Submission $sub)
  {
    return $user->id === $sub->user_id
      ? Response::allow()
      : response()->json([
        'error' => 'You don\'t have permission to view this client.'
      ], 400);
  }
}
