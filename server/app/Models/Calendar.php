<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Calendar extends Model
{
    use HasFactory;

    protected $guarded =  ['id', 'user_id'];

    /// Relationships
    function user()
    {
        return $this->belongsTo(User::class);
    }

    /// Methods
    public function hoursFor($dayName)
    {
        $day = $this->schedule[$dayName];

        $open = Carbon::createFromFormat('H:i a', $day['open']);
        $close = Carbon::createFromFormat('H:i a', $day['close']);

        return $close->diff($open)->h;
    }
}



// calendar 
// id 

// users
// id 

// appointments 
// id 
// user id
