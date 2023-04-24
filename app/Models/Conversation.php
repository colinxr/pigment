<?php

namespace App\Models;

use App\Models\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
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

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    ///
    // Methods
    /// 

    public function newMessage(User|Client $sender, string $body)
    {
        return $this->messages()->create([
            'sender_id' => $sender->id,
            'sender_type' => get_class($sender),
            'body' => $body,
        ]);
    }
}
