<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use \App\Traits\BelongsToTenant;

class Booking extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'hotel_id', 'guest_id', 'user_id', 'booking_number', 'status',
        'total_amount', 'tax_amount', 'booking_type',
        'notes', 'adults', 'children', 'checked_in_at', 'checked_out_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($booking) {
            if (empty($booking->booking_number)) {
                $booking->booking_number = 'BK-' . strtoupper(substr(uniqid(), -6));
            }
        });
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function items()
    {
        return $this->hasMany(BookingItem::class);
    }

    public function charges()
    {
        return $this->hasMany(BookingCharge::class);
    }

    public function addOns()
    {
        return $this->hasMany(BookingAddOn::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
