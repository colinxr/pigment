<?php

namespace App\Models;

use App\Models\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{

    use HasFactory;

    protected $guarded = ['id'];

    // protected static function booted(): void
    // {
    //     static::creating(function (Model $model) {
    //         $model->user_id = $model->submission->user_id;
    //         $model->client_id = $model->submission->client_id;
    //     });
    // }

    ///
    // Relationships
    // 
    public function artist()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
