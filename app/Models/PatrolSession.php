<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatrolSession extends Model
{
    protected $fillable = [
        'user_id',
        'shift_session_id',
        'status',
        'open_flag',
        'start_at',
        'start_lat',
        'start_lon',
        'end_at',
        'end_lat',
        'end_lon',
        'client_start_id',
        'client_end_id',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
    ];

    function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shiftSession()
    {
        return $this->belongsTo(ShiftSession::class);
    }

    public function patrolCheckpoints()
    {
        return $this->hasMany(PatrolCheckpoint::class);
    }

    public static function scopeActiveFor($q, $userId)
    {
        return $q->where('user_id', $userId)->where('status', 'active');
    }
}
