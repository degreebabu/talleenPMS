<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use \App\Traits\BelongsToTenant;

class DynamicRecord extends Model
{
    use BelongsToTenant;
    protected $fillable = ['dynamic_module_id', 'hotel_id', 'user_id'];

    public function module()
    {
        return $this->belongsTo(DynamicModule::class, 'dynamic_module_id');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function values()
    {
        return $this->hasMany(DynamicValue::class);
    }
}
