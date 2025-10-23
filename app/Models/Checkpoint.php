<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Checkpoint extends Model
{
    protected $fillable = [
        'location_id',
        'name',
        'qr_code',
        'latitude',
        'longitude',
        'radius_meter',
        'is_active',
    ];

    protected static function booted()
    {
        static::creating(function ($checkpoint) {
            if (empty($checkpoint->qr_code)) {
                $checkpoint->qr_code = (string) Str::uuid();
            }
        });
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
