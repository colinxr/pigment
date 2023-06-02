<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
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
}
