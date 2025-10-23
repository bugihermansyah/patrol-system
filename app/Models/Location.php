<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public function staff()
    {
        return $this->hasMany(LocationStaff::class, 'location_id');
    }

    public function checkpoints()
    {
        return $this->hasMany(Checkpoint::class);
    }
}
