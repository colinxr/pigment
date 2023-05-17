<?php

namespace App\Models;

use App\Models\Submission;
use App\Models\Conversation;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Message extends Model implements HasMedia
{
    use InteractsWithMedia, HasFactory;

    protected $guarded = ['id'];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function sender()
    {
        return $this->morphTo();
    }

    public function recipient(): string
    {
        return get_class($this->sender) === "App\Models\User" ?
            $this->submission->client->email :
            $this->submission->user->email;
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
