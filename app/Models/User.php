<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use App\Models\Submission;
use App\Models\Appointment;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
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

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
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

    public function getAccessToken()
    {
        return json_decode($this->access_token);
    }

    public function storeTokens(array $token)
    {
        $this->storeAccessToken($token);
        $this->storeRefreshToken($token['refresh_token']);
    }

    public function storeAccessToken(array $token)
    {
        $this->access_token = json_encode($token);
        $this->save();
    }

    public function storeRefreshToken(string $refreshToken)
    {
        $this->refresh_token = json_encode($refreshToken);
        $this->save();
    }

    public function isTokenExpired()
    {
        $expires_at = Carbon::createFromTimestamp($this->getAccessToken()->created + $this->getAccessToken()->expires_in);
        return $expires_at < Carbon::now();
    }
}
