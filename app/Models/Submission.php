<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    ///
    // Relationships
    // 
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function conversation()
    {
        return $this->hasOne(Conversation::class);
    }

    public function appointment()
    {
        return $this->hasMany(Appointment::class);
    }
}
