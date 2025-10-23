<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShiftSession extends Model
{
    protected $fillable = ['user_id','shift_id','started_at','ended_at'];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function shift(): BelongsTo { return $this->belongsTo(Shift::class); }

    public function patrolSessions()
    {
        return $this->hasMany(PatrolSession::class);
    }
}
