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
    private function santizeDay($dayName)
    {
        return strtolower($dayName);
    }

    public function hoursFor($dayName)
    {
        $day = $this->santizeDay($dayName);

        if (!$this->userWorksToday($day)) return 0;

        $dayInSchedule = $this->schedule[$day];

        $open = Carbon::createFromFormat('H:i a', $dayInSchedule['open']);
        $close = Carbon::createFromFormat('H:i a', $dayInSchedule['close']);

        return $close->diff($open)->h;
    }

    public function userWorksToday(string $dayName)
    {
        $day = $this->santizeDay($dayName);

        return array_key_exists($day, $this->schedule);
    }

    public function getHoursOpening($carbonDate, $getString = false,)
    {
        $dayName = $carbonDate->format('l');

        $hours = $this->getHoursForDay($dayName, 'open');

        return !$getString ? $carbonDate->setTimeFromTimeString($hours) :
            $carbonDate->setTimeFromTimeString($hours)->toDateTimeString();
    }

    public function getHoursClosing($carbonDate, $getString = false,)
    {
        $dayName = $carbonDate->format('l');

        $hours = $this->getHoursForDay($dayName, 'close');

        return !$getString ? $carbonDate->setTimeFromTimeString($hours) :
            $carbonDate->setTimeFromTimeString($hours)->toDateTimeString();
    }

    public function getHoursForDay($dayName, $key)
    {
        if (!$this->userWorksToday($dayName)) return null;

        $day = $this->santizeDay($dayName);

        return $this->schedule[$day][$key];
    }
}



// calendar 
// id 

// users
// id 

// appointments 
// id 
// user id
