<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use App\Models\Calendar;
use App\Models\Submission;
use App\Models\Appointment;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notifiable;
use App\Interfaces\GoogleCalendarInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'username',
        'access_token',
        'calendar_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'access_token' => 'array',
    ];

    ///
    // Relationships
    /// 

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function client($email)
    {
        return $this->hasMany(Client::class)->where('email', $email)->first();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    function upcomingAppointsments()
    {
        return $this->appointments()->where(['startDateTime', '>', Carbon::now()]);
    }

    public function appointmentsForClient(int $client_id)
    {
        return $this->appointments()->whereHas('client', function ($query) use ($client_id) {
            $query->where('id', $client_id);
        })->get();
    }

    public function calendar()
    {
        return $this->hasOne(Calendar::class);
    }

    ///
    // Methods
    ///
    public function isTokenExpired()
    {
        if (!$this->access_token || isset($this->access_token['error'])) return true;

        $expires_at = Carbon::createFromTimestamp($this->access_token['created'] + $this->access_token['expires_in']);
        return $expires_at < Carbon::now();
    }

    public function subscribeToCalendarUpdates()
    {
        # code...
    }

    public function updateCalendar(GoogleCalendarInterface $gCalService, string $calendarId)
    {
        $this->update(['calendar_id' => $calendarId]);

        $webhookUrl = url("/api/users/{$this->id}/calendar/subscribe");

        $watchRequest = $gCalService->watchCalendar($calendarId, $webhookUrl);
    }

    function appointsmentsGroupedByDate($dateTime, $offset = 0)
    {
        return $this->appointments()->upcoming($dateTime)
            ->offset($offset)
            ->limit(100)
            ->get()
            ->sortBy('startDateTime')
            ->groupBy(fn ($appt) => $appt->startDateTime->format('Y-m-d'))
            ->map(function ($grouped) {
                return [
                    'totalTime' => $grouped->sum(function ($appt) {
                        $start = Carbon::parse($appt->startDateTime);
                        $end = Carbon::parse($appt->endDateTime);
                        return $end->diffInMinutes($start) / 60;
                    }),
                    'appointments' => $grouped,
                ];
            });
    }

    function getNextAvailableSlots(int $duration, $timeToQuery = null)
    {
        $availableSlots = [];
        $grouped_appts = $this->appointsmentsGroupedByDate($timeToQuery);

        // dump($grouped_appts);

        $firstDay = Carbon::createFromFormat('Y-m-d', $grouped_appts->keys()->first());
        $lastDay = Carbon::createFromFormat('Y-m-d', $grouped_appts->keys()->last());
        while ($firstDay <= $lastDay) {
            // if today is not a day in the user's schedule, then today won't work. 
            $dayName = strtolower($firstDay->format('l'));

            if (!array_key_exists($dayName, $this->calendar->schedule)) {
                $firstDay->addDay();
                continue;
            }

            // if today is a working day, but doesn't have any appointments scheduled
            // then lets return when the user starts work for the day.  
            $date = $firstDay->format('Y-m-d');

            if (!$grouped_appts->has($date)) {
                $openTime = $this->calendar->schedule[$dayName]['open'];
                $dateTime = $firstDay->setTimeFromTimeString($openTime);

                $availableSlots[] = [
                    'dateTime' => $dateTime->toDateTimeString(),
                    'message' => 'Nothing scheduled this day',
                ];

                if (count($availableSlots) === 3) break;

                $firstDay->addDay();
                continue;
            }

            ['totalTime' => $totalTime, 'appointments' => $appointments] = $grouped_appts[$date];

            //if the total number of hours booked is greater 
            //than the duration of the new appointment
            // then today simply will not work. 
            $scheduledHours = $this->calendar->hoursFor($dayName);

            if (($scheduledHours - $totalTime) < $duration) {
                $firstDay->addDay();
                continue;
            }

            // if there's only one appointment booked,
            // let's return the first availability. 
            if ($appointments->count() === 1) {
                $availableSlots[] = [
                    'dateTime' => $appointments->first()->endDateTime,
                    'message' => 'Today is open anytime after this.',
                ];

                if (count($availableSlots) === 3) break;

                $firstDay->addDay();
                continue;
            }

            foreach ($appointments as $key => $appt) {
                $firstTime = $appt->endDateTime;
                $secondTime = null;

                // if there's no next appointment this day,
                // let's set the $endTime to be equal to they end of the work day. 
                if ($key === $appointments->count() - 1) {
                    $closing = $this->calendar->schedule[$dayName]['close'];
                    $secondTime = $firstDay->setTimeFromTimeString($closing);
                } else {
                    $secondTime = $appointments[$key + 1]->startDateTime;
                }

                $gap = $secondTime->diff($firstTime);

                if ($gap->h >= $duration) {
                    dump($appt->endDateTime);
                    $availableSlots[] = ['dateTime' => $appt->endDateTime,];

                    if (count($availableSlots) === 3) break;
                }
            };

            $firstDay->addDay();
        }

        if (count($availableSlots) < 3) {
            while (count($availableSlots) <= 3) {
                $lastDay->addDay();
                $dayName = strtolower($lastDay->format('l'));

                if (!array_key_exists($dayName, $this->calendar->schedule)) {
                    continue;
                }

                $openTime = $this->calendar->schedule[$dayName]['open'];
                $dateTime = $lastDay->setTimeFromTimeString($openTime);

                $availableSlots[] = [
                    'dateTime' => $dateTime->toDateTimeString(),
                    'message' => 'Nothing scheduled this day',
                ];
            }
        }

        return $availableSlots;
    }
}
