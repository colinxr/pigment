<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    ///
    // Relationships
    // 

    public function artists()
    {
        return $this->belongsToMany(User::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
