<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use \App\Traits\BelongsToTenant;

class BookingCharge extends Model
{
    use BelongsToTenant;
    protected $fillable = [
        'booking_id',
        'description',
        'amount',
        'charge_type',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
