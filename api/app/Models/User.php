<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use App\Models\Calendar;
use App\Models\Submission;
use App\Models\Appointment;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
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

    public function lastestAppointments()
    {
        return $this->hasMany(Appointment::class)->latest();
    }

    function upcomingAppointsments()
    {
        return $this->appointments()->where(['startDateTime', '>', Carbon::now()]);
    }

    public function appointmentsForClient(int $client_id)
    {
        return DB::select(
            "
            SELECT a.*
            FROM appointments AS a
            INNER JOIN submissions AS s ON a.submission_id = s.id
            WHERE s.client_id = :client_id AND s.user_id = :user_id",
            [
                'client_id' => $client_id,
                'user_id' => $this->id
            ]
        );
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
}
