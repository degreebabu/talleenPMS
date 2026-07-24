<!DOCTYPE html>
@php use Illuminate\Support\Facades\Storage; @endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @php $hotel = auth()->user()->hotel ?? null; @endphp
    @if($hotel)
    <style>
        :root {
            --primary: {{ $hotel->primary_color }};
            --accent:  {{ $hotel->secondary_color }};
            --brand-primary: {{ $hotel->primary_color }};
        }
    </style>
    @endif
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900 selection:bg-blue-200 selection:text-blue-900">

@if(session()->has('impersonate'))
<div class="bg-blue-600 text-white px-4 py-2 flex items-center justify-between z-50 relative shadow-md">
    <div class="text-sm font-semibold flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        You are currently impersonating a tenant.
    </div>
    <a href="{{ route('admin.leave-impersonate') }}" class="px-3 py-1 bg-white text-blue-600 rounded-md shadow-sm hover:bg-blue-50 transition text-xs font-bold">
        Return to Superadmin
    </a>
</div>
@endif

<div class="flex @if(session()->has('impersonate')) h-[calc(100%-40px)] @else h-full @endif">

    {{-- Sidebar --}}
    <aside class="w-64 flex-shrink-0 bg-white border-r border-slate-200 flex flex-col shadow-sm z-20" id="sidebar">

        {{-- Logo & Property Switcher --}}
        <div class="px-6 py-5 border-b border-slate-100 relative" x-data="{ openSwitcher: false }">
            <div class="flex items-center justify-between cursor-pointer group" @click="openSwitcher = !openSwitcher">
                <div class="flex items-center gap-3 min-w-0">
                    @if($hotel?->logo_path)
                        <img src="{{ Storage::url($hotel->logo_path) }}" class="w-10 h-10 rounded-xl object-cover border border-slate-100 shadow-sm flex-shrink-0">
                    @else
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-sm flex-shrink-0"
                             style="background: {{ $hotel?->primary_color ?? '#1e40af' }}">
                            {{ strtoupper(substr($hotel?->name ?? 'T', 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <div class="font-bold text-sm text-slate-900 truncate group-hover:text-blue-600 transition">{{ $hotel?->name ?? 'TalleenPMS' }}</div>
                        <div class="text-[11px] text-slate-500 font-medium uppercase tracking-wider">Admin Panel</div>
                    </div>
                </div>
                
                @if(auth()->check() && auth()->user()->accessibleHotels && auth()->user()->accessibleHotels->count() > 1)
                <div class="text-slate-400 group-hover:text-blue-600 transition ml-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                </div>
                @endif
            </div>

            @if(auth()->check() && auth()->user()->accessibleHotels && auth()->user()->accessibleHotels->count() > 1)
            <div x-show="openSwitcher" 
                 @click.outside="openSwitcher = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute top-full left-4 right-4 mt-2 bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden z-50" style="display: none;">
                <div class="py-1 max-h-64 overflow-y-auto">
                    <div class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider bg-slate-50/50">Switch Property</div>
                    @foreach(auth()->user()->accessibleHotels as $accessibleHotel)
                        <a href="{{ route('admin.switch-property', $accessibleHotel->id) }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-2 {{ $accessibleHotel->id === $hotel?->id ? 'bg-blue-50/50 font-medium text-blue-700' : '' }}">
                            @if($accessibleHotel->id === $hotel?->id)
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <div class="w-4"></div>
                            @endif
                            <span class="truncate">{{ $accessibleHotel->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            @php
            $navItems = [
                ['icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Dashboard', 'route' => 'admin.dashboard'],
                ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'label' => 'Bookings', 'route' => 'admin.bookings.index'],
                ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Tape Chart', 'route' => 'admin.calendar'],
                ['icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'label' => 'Room Categories', 'route' => 'admin.rooms.categories'],
                ['icon' => 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z', 'label' => 'All Rooms', 'route' => 'admin.rooms.index'],
            ];
            @endphp

            @php
            $pmsItems = [
                ['icon' => 'M3 4a1 1 0 011-1h16a1 1 0 011 1v3a1 1 0 01-.293.707L13 15.414V21a1 1 0 01-.553.894l-4 2A1 1 0 017 23v-7.586L3.293 7.707A1 1 0 013 7V4z', 'label' => 'Housekeeping', 'route' => 'admin.pms.housekeeping'],
                ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'label' => 'Guest CRM', 'route' => 'admin.pms.guests'],
            ];
            $posItems = [
                ['icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z', 'label' => 'POS Terminal', 'route' => 'admin.pos.terminal'],
                ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'label' => 'Restaurant Menu', 'route' => 'admin.restaurant.menu'],
                ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Folio Poster', 'route' => 'admin.charge-poster'],
            ];
            $revenueItems = [
                ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'label' => 'AI Revenue Mgmt', 'route' => 'admin.revenue'],
                ['icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064', 'label' => 'OTA Channels', 'route' => 'admin.channels'],
            ];
            $otherItems = [
                ['icon' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9', 'label' => 'Website Builder', 'route' => 'admin.website'],
                ['icon' => 'M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z', 'label' => 'Integrations', 'route' => 'admin.integrations'],
                ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'label' => 'Reports', 'route' => 'admin.reports'],
                ['icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'label' => 'Staff & Roles', 'route' => 'admin.staff-manager'],
                ['icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'label' => 'Settings', 'route' => 'admin.settings'],
            ];
            @endphp

            @foreach($navItems as $item)
            <a href="{{ route($item['route']) }}"
               @class([
                   'flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition-all duration-200',
                   'bg-blue-50 text-blue-700 font-semibold shadow-sm border border-blue-100' => request()->routeIs($item['route']),
                   'text-slate-500 hover:text-slate-900 hover:bg-slate-50 font-medium border border-transparent' => !request()->routeIs($item['route']),
               ])>
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                </svg>
                {{ $item['label'] }}
            </a>
            @endforeach

            <div class="pt-2 pb-1"><p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Housekeeping & CRM</p></div>
            @foreach($pmsItems as $item)
            <a href="{{ route($item['route']) }}" @class(['flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition-all duration-200', 'bg-blue-50 text-blue-700 font-semibold shadow-sm border border-blue-100' => request()->routeIs($item['route']), 'text-slate-500 hover:text-slate-900 hover:bg-slate-50 font-medium border border-transparent' => !request()->routeIs($item['route'])])>
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/></svg>
                {{ $item['label'] }}
            </a>
            @endforeach

            <div class="pt-2 pb-1"><p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">POS Restaurant</p></div>
            @foreach($posItems as $item)
            <a href="{{ route($item['route']) }}" @class(['flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition-all duration-200', 'bg-blue-50 text-blue-700 font-semibold shadow-sm border border-blue-100' => request()->routeIs($item['route']), 'text-slate-500 hover:text-slate-900 hover:bg-slate-50 font-medium border border-transparent' => !request()->routeIs($item['route'])])>
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/></svg>
                {{ $item['label'] }}
            </a>
            @endforeach

            <div class="pt-2 pb-1"><p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Revenue & Channels</p></div>
            @foreach($revenueItems as $item)
            <a href="{{ route($item['route']) }}" @class(['flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition-all duration-200', 'bg-blue-50 text-blue-700 font-semibold shadow-sm border border-blue-100' => request()->routeIs($item['route']), 'text-slate-500 hover:text-slate-900 hover:bg-slate-50 font-medium border border-transparent' => !request()->routeIs($item['route'])])>
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/></svg>
                {{ $item['label'] }}
            </a>
            @endforeach

            <div class="pt-2 pb-1"><p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Platform</p></div>
            @foreach($otherItems as $item)
            <a href="{{ route($item['route']) }}" @class(['flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition-all duration-200', 'bg-blue-50 text-blue-700 font-semibold shadow-sm border border-blue-100' => request()->routeIs($item['route']), 'text-slate-500 hover:text-slate-900 hover:bg-slate-50 font-medium border border-transparent' => !request()->routeIs($item['route'])])>
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/></svg>
                {{ $item['label'] }}
            </a>
            @endforeach

            @php
                $dynamicModules = \App\Models\DynamicModule::where('is_active', true)->get();
                $tenantFeatures = $hotel->features ?? [];
            @endphp
            
            @if($dynamicModules->count() > 0)
                <div class="pt-2 pb-1"><p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Custom Add-ons</p></div>
                @foreach($dynamicModules as $dMod)
                    @if(isset($tenantFeatures[$dMod->slug]) && $tenantFeatures[$dMod->slug] === true)
                    <a href="{{ route('admin.dynamic-module', $dMod->slug) }}" @class(['flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition-all duration-200', 'bg-blue-50 text-blue-700 font-semibold shadow-sm border border-blue-100' => request()->is('admin/module/'.$dMod->slug), 'text-slate-500 hover:text-slate-900 hover:bg-slate-50 font-medium border border-transparent' => !request()->is('admin/module/'.$dMod->slug)])>
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $dMod->icon }}"/></svg>
                        {{ $dMod->name }}
                    </a>
                    @endif
                @endforeach
            @endif
        </nav>

        {{-- User footer --}}
        <div class="border-t border-slate-100 px-6 py-5 bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-slate-200 flex items-center justify-center text-sm font-bold text-slate-700 border border-slate-300 shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="text-sm font-bold text-slate-900 truncate">{{ auth()->user()->name }}</div>
                    <div class="text-[11px] text-slate-500 truncate">{{ auth()->user()->email }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-red-500 transition p-1.5 hover:bg-red-50 rounded-lg" title="Logout">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main content --}}
    <main class="flex-1 overflow-y-auto relative bg-slate-50 flex flex-col">

        {{-- Top bar --}}
        <header class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-slate-200 px-8 py-5 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">{{ $header ?? 'Dashboard' }}</h1>
            </div>
            <div class="flex items-center gap-4">
                <span class="px-4 py-1.5 bg-slate-100 rounded-full text-xs font-semibold text-slate-600 border border-slate-200 shadow-sm">{{ now()->format('D, M j, Y') }}</span>
            </div>
        </header>

        <div class="p-8 w-full mx-auto min-h-[calc(100vh-160px)]">
            {{ $slot }}
        </div>

        {{-- Global Footer --}}
        <footer class="border-t border-slate-200 mt-auto py-6 text-center">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">
                Powered by <span class="text-blue-600 font-bold">TALLEEN PMS</span> &copy; {{ date('Y') }}
            </p>
        </footer>
    </main>
</div>

@livewire('admin.ai-assistant')
@livewireScripts
</body>
</html>
