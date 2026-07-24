<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use \App\Traits\BelongsToTenant;

class Guest extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'hotel_id', 'name', 'email', 'phone', 'id_proof_path',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
