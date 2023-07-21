<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Submission extends Model implements HasMedia
{
    use InteractsWithMedia, HasFactory;

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

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function latestMessages()
    {
        return $this->messages()->latest()->take(50);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    ///
    // Methods
    /// 

    public function sortedAppointments()
    {
        [$upcoming, $past] = $this->appointments()->get()
            ->partition(
                fn ($item) => $item['startDateTime']->gte(Carbon::now()),
            );

        return [
            'upcoming' => $upcoming,
            'past' => $past->toArray(),
        ];
    }

    public function newMessage(User|Client $sender, string $body, $message_id = null)
    {
        $domain = get_class($sender) === 'App\Models\User' ? '@mail.usepigment.com' : '@client.usepigment.com';

        return $this->messages()->create([
            'sender_id' => $sender->id,
            'sender_type' => get_class($sender),
            'body' => $body,
            'message_id' => $message_id ?? Str::random(10) . $domain,
            'reference_id' => $this->messages()->latest()->value('message_id'),
        ]);
    }


    /// 
    // Media 
    ///
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments');
    }
}
