<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use \App\Traits\BelongsToTenant;

class RoomCategory extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'hotel_id', 'name', 'description', 'base_price',
        'max_adults', 'max_children', 'amenities_json',
    ];

    protected $casts = [
        'amenities_json' => 'array',
        'base_price' => 'decimal:2',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function images()
    {
        return $this->hasMany(RoomCategoryImage::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function getCapacityAttribute()
    {
        return $this->max_adults + $this->max_children;
    }
}
