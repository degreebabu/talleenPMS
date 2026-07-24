<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DynamicModule extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'icon', 'is_active'];

    public function fields()
    {
        return $this->hasMany(DynamicField::class)->orderBy('order');
    }

    public function records()
    {
        return $this->hasMany(DynamicRecord::class);
    }
}
