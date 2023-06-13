<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    use HasFactory;

    protected $guarded =  ['id', 'user_id'];

    /// Relationships
    function user()
    {
        return $this->belongsTo(User::class);
    }
}
