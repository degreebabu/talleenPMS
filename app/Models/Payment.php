<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use \App\Traits\BelongsToTenant;

class Payment extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'booking_id', 'gateway', 'transaction_id', 'amount', 'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
