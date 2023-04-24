<?php

namespace App\Models;

use App\Models\Conversation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender()
    {
        return $this->morphTo();
    }

    public function recipient(): string
    {
        // if the sender is a user 
        // then the recipient is their client
        // and vice versa 
        return get_class($this->sender) === "App\Models\User" ?
            $this->conversation->client->email :
            $this->conversation->user->email;
    }
}
