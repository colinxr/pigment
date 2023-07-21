<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['initials', 'full_name'];

    protected static function boot()
    {
        // Boot other traits on the Model
        parent::boot();

        static::creating(function ($model) {
            $model->setAttribute('uuid', Str::uuid()->toString());
        });
    }

    ///
    // Relationships
    // 

    public function artists()
    {
        return $this->belongsToMany(User::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function getInitialsAttribute()
    {
        return strtoupper(
            substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1)
        );
    }

    public function getFullNameAttribute()
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }
}
