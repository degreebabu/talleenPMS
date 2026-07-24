<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use \App\Traits\BelongsToTenant;

class PosOrderItem extends Model
{
    use BelongsToTenant;
    protected $fillable = [
        'pos_order_id', 'item_name', 'quantity', 'unit_price', 'total_price', 'notes',
    ];

    public function order() { return $this->belongsTo(PosOrder::class, 'pos_order_id'); }
}
