<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use \App\Traits\BelongsToTenant;

class DayPass extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'visit_date' => 'date',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function package()
    {
        return $this->belongsTo(DayPackage::class, 'day_package_id');
    }
}
