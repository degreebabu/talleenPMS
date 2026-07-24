<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ── Public welcome & booking ────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
});

Route::get('/book/{hotel:subdomain}', function (\App\Models\Hotel $hotel) {
    return view('public.book', compact('hotel'));
})->name('public.book');

// ── Auth routes (Breeze) ────────────────────────────────────────────────────
require __DIR__.'/auth.php';

// ── Authenticated routes ────────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Hotel Onboarding wizard
    Route::get('/onboarding', function () {
        if (auth()->user()->hotel_id) {
            return redirect()->route('admin.dashboard');
        }
        return view('onboarding');
    })->name('onboarding');

    // Redirect /dashboard based on role
    Route::get('/dashboard', function () {
        if (auth()->user()->hasRole('super_admin')) {
            return redirect()->route('super-admin.dashboard');
        }
        if (! auth()->user()->hotel_id) {
            return redirect()->route('onboarding');
        }
        return redirect()->route('admin.dashboard');
    })->name('dashboard');

    // ── Public Hotel Landing Page ─────────────────────────────────────────────
    Route::get('/{hotel:subdomain}', App\Livewire\LandingPage::class)->name('landing-page');

    // ── Public Booking Engine ────────────────────────────────────────────────
    Route::get('/book/{hotel:subdomain}', App\Livewire\BookingEngine::class)->name('book');

    // ── Super Admin area ────────────────────────────────────────────────────
    Route::prefix('super-admin')->name('super-admin.')->middleware('role:super_admin')->group(function () {
        Route::get('/dashboard', \App\Livewire\SuperAdmin\Dashboard::class)->name('dashboard');
        Route::get('/companies', \App\Livewire\SuperAdmin\CompanyManager::class)->name('companies');
        Route::get('/module-manager', \App\Livewire\SuperAdmin\ModuleManager::class)->name('module-manager');
        Route::get('/module-builder', \App\Livewire\SuperAdmin\ModuleBuilder::class)->name('module-builder');
        Route::get('/reports', \App\Livewire\SuperAdmin\Reports::class)->name('reports');
        Route::get('/hotels', \App\Livewire\SuperAdmin\TenantManager::class)->name('hotels');
        Route::get('/activity-logs', \App\Livewire\SuperAdmin\ActivityLogs::class)->name('activity-logs');
        Route::get('/plans', \App\Livewire\SuperAdmin\PlanManager::class)->name('plans');
        Route::get('/settings', \App\Livewire\SuperAdmin\PlatformSettings::class)->name('settings');
        
        // Impersonate
        Route::get('/impersonate/{hotel_id}', function($hotel_id) {
            $hotel = App\Models\Hotel::findOrFail($hotel_id);
            $adminUser = App\Models\User::where('hotel_id', $hotel->id)->first();
            if ($adminUser) {
                session()->put('impersonate', auth()->id());
                auth()->login($adminUser);
                return redirect()->route('admin.dashboard')->with('success', 'You are now impersonating ' . $hotel->name);
            }
            return back()->with('error', 'No admin user found for this hotel.');
        })->name('impersonate');
    });

    // ── Tenant Admin area ───────────────────────────────────────────────────
    Route::prefix('admin')->name('admin.')->middleware(['auth', 'has_hotel'])->group(function () {
        Route::get('/leave-impersonate', function() {
            if (session()->has('impersonate')) {
                auth()->loginUsingId(session()->pull('impersonate'));
                return redirect()->route('super-admin.hotels')->with('success', 'Left impersonation.');
            }
            return redirect()->route('admin.dashboard');
        })->name('leave-impersonate');

        // Property Switcher
        Route::get('/switch-property/{hotel_id}', function($hotel_id) {
            $user = auth()->user();
            if ($user->accessibleHotels->contains('id', $hotel_id)) {
                $user->update(['hotel_id' => $hotel_id]);
                return redirect()->route('admin.dashboard')->with('success', 'Switched to new property.');
            }
            abort(403);
        })->name('switch-property');

        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // Corporate Overview (Group Manager)
        // Route::get('/corporate-overview', \App\Livewire\Admin\CorporateOverview::class)->name('corporate-overview');
        
        // Bookings
        Route::get('/bookings', function() { return view('admin.bookings'); })->name('bookings.index');
        Route::get('/bookings/{booking}/invoice', function(App\Models\Booking $booking) {
            $hotel = auth()->user()->hotel;
            if ($booking->hotel_id !== $hotel->id) abort(403);
            return view('admin.invoice', compact('booking', 'hotel'));
        })->name('bookings.invoice');
        
        Route::get('/bookings/{booking}/folio', App\Livewire\Admin\FolioManager::class)->name('bookings.folio');

        Route::get('/rooms', [DashboardController::class, 'rooms'])->name('rooms.categories');
        Route::get('/rooms/all', [DashboardController::class, 'roomList'])->name('rooms.index');
        Route::get('/calendar', function() { return view('admin.calendar'); })->name('calendar');
        Route::get('/website', function() { return view('admin.website'); })->name('website');
        
        // Banquets
        Route::middleware('feature:banquets')->group(function () {
            Route::get('/banquets/spaces', function() { return view('admin.banquet.spaces'); })->name('banquet.spaces');
            Route::get('/banquets/calendar', function() { return view('admin.banquet.calendar'); })->name('banquet.calendar');
        });

        // Day Outings
        Route::middleware('feature:outings')->group(function () {
            Route::get('/outings/packages', function() { return view('admin.outing.packages'); })->name('outing.packages');
            Route::get('/outings/passes', function() { return view('admin.outing.passes'); })->name('outing.passes');
        });

        // PMS & CRM
        Route::get('/pms/housekeeping', function() { return view('admin.pms.housekeeping'); })->name('pms.housekeeping');
        Route::get('/pms/guests', function() { return view('admin.pms.guests'); })->name('pms.guests');
        Route::get('/pms/folio/{booking}', function(\App\Models\Booking $booking) { return view('admin.pms.folio', compact('booking')); })->name('pms.folio');

        // POS
        Route::get('/pos', function() { return view('admin.pos.terminal'); })->name('pos.terminal');

        // Revenue Management
        Route::get('/revenue', function() { return view('admin.revenue'); })->name('revenue');

        // OTA Channel Manager
        Route::get('/channels', function() { return view('admin.channels'); })->name('channels');

        // Integrations
        Route::get('/integrations', function() { return view('admin.integrations'); })->name('integrations');

        // Restaurant & Staff
        Route::get('/restaurant/menu', \App\Livewire\Admin\Restaurant\MenuManager::class)->name('restaurant.menu');
        Route::get('/staff-manager', \App\Livewire\Admin\StaffManager::class)->name('staff-manager');
        Route::get('/charge-poster', \App\Livewire\Admin\ChargePoster::class)->name('charge-poster');
        Route::get('/module/{slug}', \App\Livewire\Admin\DynamicModuleViewer::class)->name('dynamic-module');

        // Settings & Reports
        Route::get('/settings', function() { return view('admin.settings'); })->name('settings');
        Route::get('/reports', function() { return view('admin.reports'); })->name('reports');
    });
});
