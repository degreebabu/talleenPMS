<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use \App\Traits\BelongsToTenant;

class Invoice extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'booking_id', 'invoice_number', 'pdf_path', 'generated_at', 'gst_breakdown_json',
    ];

    protected $casts = [
        'gst_breakdown_json' => 'array',
        'generated_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
