<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PatrolCheckpoint extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'patrol_session_id',
        'checkpoint_id',
        'qr_scanned',
        'is_valid',
        'scanned_at',
        'lat',
        'lon',
        'note',
        'client_id'
    ];
    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function patrolSession()
    {
        return $this->belongsTo(PatrolSession::class);
    }

    public function checkpoint()
    {
        return $this->belongsTo(Checkpoint::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('checkpoint_photos')
            ->useDisk('public');
    }

    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(512)
            ->height(512)
            ->sharpen(8)
            ->nonQueued();
    }

    public function photosPayload()
    {
        return $this->getMedia('photos')->map(function($m){
            return [
                'id'    => $m->id,
                'name'  => $m->file_name,
                'mime'  => $m->mime_type,
                'size'  => $m->size,
                'url'   => $m->getUrl(),           // url file asli
                'thumb' => $m->getUrl('thumb'),    // url thumbnail
            ];
        })->values();
    }
}
