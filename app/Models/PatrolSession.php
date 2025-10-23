<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatrolSession extends Model
{
    protected $fillable = [
        'shift_session_id',
        'status',
        'open_flag',
        'start_at',
        'start_lat',
        'start_lon',
        'end_at',
        'end_lat',
        'end_lon',
    ];

    public function shiftSession()
    {
        return $this->belongsTo(ShiftSession::class);
    }

    public function patrolCheckpoints()
    {
        return $this->hasMany(PatrolCheckpoint::class);
    }
}
