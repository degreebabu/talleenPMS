<?php

namespace App\Traits;

use App\Scopes\TenantScope;
use Illuminate\Support\Facades\Auth;
use App\Models\Hotel;

trait BelongsToTenant
{
    /**
     * Boot the trait for a model.
     */
    protected static function bootBelongsToTenant()
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            if (Auth::check() && !$model->hotel_id) {
                $user = Auth::user();
                if ($user->hotel_id) {
                    $model->hotel_id = $user->hotel_id;
                }
            }
        });
    }

    /**
     * Relationship to the Hotel model.
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
