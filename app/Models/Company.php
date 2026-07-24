<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_email',
        'contact_phone',
        'address',
        'gst_number',
        'registration_number',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }
    
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
