<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'name', 'subdomain', 'address', 'gst_number', 'registration_number',
        'primary_color', 'secondary_color',
        'logo_path', 'cover_path',
        'contact_email', 'contact_phone',
        'check_in_time', 'check_out_time',
        'subscription_plan_id', 'subscription_status', 'subscription_ends_at',
        'features', 'documents', 'is_active',
    ];

    protected $casts = [
        'subscription_ends_at' => 'datetime',
        'features' => 'array',
        'documents' => 'array',
        'is_active' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function website()
    {
        return $this->hasOne(HotelWebsite::class);
    }

    public function roomCategories()
    {
        return $this->hasMany(RoomCategory::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function banquetHalls()
    {
        return $this->hasMany(BanquetHall::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }
}
