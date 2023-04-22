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
    public function artist()
    {
        return $this->belongsT(User::class);
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
