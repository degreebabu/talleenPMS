<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use \App\Traits\BelongsToTenant;

class RestaurantMenuItem extends Model
{
    use BelongsToTenant;
    protected $fillable = [
        'hotel_id', 'name', 'description', 'price', 'category', 'is_available', 'image_url'
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
