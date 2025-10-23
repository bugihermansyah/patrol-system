<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatrolCheckpoint extends Model
{
    protected $fillable = [
        'patrol_session_id',
        'checkpoint_id',
        'qr_scaned',
        'is_valid',
        'scanned_at',
        'lat',
        'lon',
        'photo_url',
        'remaks'
    ];

    public function patrolSession()
    {
        return $this->belongsTo(PatrolSession::class);
    }

    public function checkpoint()
    {
        return $this->belongsTo(Checkpoint::class);
    }
}
