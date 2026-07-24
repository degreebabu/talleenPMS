<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DynamicField extends Model
{
    protected $fillable = ['dynamic_module_id', 'name', 'type', 'is_required', 'order'];

    public function module()
    {
        return $this->belongsTo(DynamicModule::class, 'dynamic_module_id');
    }
}
