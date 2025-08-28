<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    protected $fillable = [
        'calendar',
        'staff_id',
        'user_id',
        'type',
        'day_in',
        'day_out',
        'hours',
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
