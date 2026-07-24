<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use \App\Traits\BelongsToTenant;

class RateRule extends Model
{
    use BelongsToTenant;
    protected $fillable = [
        'hotel_id', 'room_category_id', 'name', 'rule_type', 'start_date', 'end_date', 
        'adjustment_type', 'adjustment_value', 'min_occupancy_percent', 'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function roomCategory()
    {
        return $this->belongsTo(RoomCategory::class);
    }
}
