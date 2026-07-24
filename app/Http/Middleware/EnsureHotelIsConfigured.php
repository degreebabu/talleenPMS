<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHotelIsConfigured
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->user()?->hotel_id) {
            return redirect()->route('onboarding');
        }
        return $next($request);
    }
}
