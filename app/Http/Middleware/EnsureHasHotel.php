<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasHotel
{
    public function handle(Request $request, Closure $next): Response
    {
        // Allow the leave-impersonate route to pass through always
        if ($request->routeIs('admin.leave-impersonate')) {
            return $next($request);
        }

        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->hotel_id) {
            // If they are super admin, redirect to super admin dashboard
            if (auth()->user()->role === 'super_admin') {
                return redirect()->route('super-admin.dashboard')->with('error', 'You must impersonate a tenant to view the tenant admin area.');
            }
            return redirect()->route('onboarding')->with('error', 'You must create a hotel first.');
        }

        return $next($request);
    }
}
