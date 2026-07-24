<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantFeature
{
    public function handle(Request $request, Closure $next, $feature): Response
    {
        $hotel = auth()->user()->hotel;
        
        if (!$hotel) {
            return redirect()->route('admin.dashboard')->with('error', 'No tenant associated.');
        }

        if (!$hotel->is_active) {
            return redirect()->route('admin.dashboard')->with('error', 'Your tenant account has been suspended by the superadmin.');
        }

        // Features can be overridden directly on the hotel, otherwise fallback to subscription plan
        $hotelFeatures = $hotel->features;
        
        if (is_array($hotelFeatures)) {
            $features = $hotelFeatures;
        } else {
            $features = $hotel->subscriptionPlan ? ($hotel->subscriptionPlan->features ?? []) : [];
        }
        
        if (!in_array($feature, $features)) {
            return redirect()->route('admin.dashboard')->with('error', 'Your current plan does not include the ' . ucfirst($feature) . ' module. Please contact support.');
        }

        return $next($request);
    }
}
