<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use \App\Traits\BelongsToTenant;

class PosOrder extends Model
{
    use BelongsToTenant;
    protected $fillable = [
        'hotel_id', 'booking_id', 'order_number', 'table_number',
        'status', 'payment_method', 'subtotal', 'tax_amount', 'total_amount', 'notes',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            $order->order_number = 'POS-' . strtoupper(substr(uniqid(), -6));
        });
    }

    public function hotel() { return $this->belongsTo(Hotel::class); }
    public function booking() { return $this->belongsTo(Booking::class); }
    public function items() { return $this->hasMany(PosOrderItem::class); }
}
