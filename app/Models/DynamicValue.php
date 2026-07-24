<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DynamicValue extends Model
{
    protected $fillable = ['dynamic_record_id', 'dynamic_field_id', 'value_text', 'value_number', 'value_boolean', 'value_date'];

    public function record()
    {
        return $this->belongsTo(DynamicRecord::class, 'dynamic_record_id');
    }

    public function field()
    {
        return $this->belongsTo(DynamicField::class, 'dynamic_field_id');
    }
}
