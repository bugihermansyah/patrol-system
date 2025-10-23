<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationStaff extends Model
{
    public function location()
    {
        return $this->belongsTo(PatrolLocation::class, 'location_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
