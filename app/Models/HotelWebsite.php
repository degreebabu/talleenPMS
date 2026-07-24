<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use \App\Traits\BelongsToTenant;

class HotelWebsite extends Model
{
    use BelongsToTenant;
    protected $fillable = [
        'hotel_id',
        'hero_title',
        'hero_subtitle',
        'about_text',
        'video_url',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'gallery_images',
        'is_published',
        'google_map_embed',
        'google_reviews_embed',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'is_published' => 'boolean',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
