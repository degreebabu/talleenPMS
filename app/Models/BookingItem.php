<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use \App\Traits\BelongsToTenant;

class BookingItem extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function item()
    {
        return $this->morphTo();
    }
}
