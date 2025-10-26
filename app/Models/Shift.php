<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
     protected $fillable = [
          'location_id',
          'name',
          'start_time',
          'end_time',
          'start_tolerance_min',
          'end_tolerance_min',
          'min_duration_min',
          'is_active',
     ];

     public function location()
     {
          return $this->belongsTo(Location::class);
     }
}
