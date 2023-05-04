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
        'username'
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

    public function getToken(string $name)
    {
        return $this->tokens()->where('name', $name)->first();
    }

    public function accessToken()
    {
        return $this->getToken('google_access_token');
    }

    public function refreshToken()
    {
        return $this->getToken('google_refresh_token');
    }

    public function storeGCalTokens($token)
    {
        $this->storeGCalAccessToken($token);
        $this->storeGCalRefreshToken($token['refresh_token']);
    }

    public function storeGCalAccessToken($token)
    {
        $expiresAt = $token['expires_in'] + $token['created'];

        $stored_token = $this->tokens()->create([
            'name' => 'google_access_token',
            'token' => $token['access_token'],
            'abilities' => '*',
            'expires_at' => Carbon::createFromTimestamp($expiresAt)->toDateTimeString(),
        ]);

        return $stored_token;
    }

    public function storeGCalRefreshToken($refreshToken)
    {
        $token = $this->tokens()->create([
            'name' => 'google_refresh_token',
            'token' => $refreshToken,
            'abilities' => '*',
        ]);

        return $token;
    }
}
