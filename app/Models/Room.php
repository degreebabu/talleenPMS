<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use \App\Traits\BelongsToTenant;

class Room extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'hotel_id', 'room_category_id', 'room_number', 'floor', 'status',
    ];

    const STATUSES = ['available', 'occupied', 'maintenance', 'dirty'];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function category()
    {
        return $this->belongsTo(RoomCategory::class, 'room_category_id');
    }

    public function bookingItems()
    {
        return $this->morphMany(BookingItem::class, 'item');
    }
}
