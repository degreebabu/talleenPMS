<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $hotel->name ?? 'Booking' }} — TalleenPMS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        :root {
            --brand-primary: {{ $hotel->primary_color ?? '#1e40af' }};
            --brand-secondary: {{ $hotel->secondary_color ?? '#f59e0b' }};
        }
        
        .bg-brand-primary { background-color: var(--brand-primary); }
        .text-brand-primary { color: var(--brand-primary); }
        .border-brand-primary { border-color: var(--brand-primary); }
        .ring-brand-primary { --tw-ring-color: var(--brand-primary); }
        
        .bg-brand-secondary { background-color: var(--brand-secondary); }
        .text-brand-secondary { color: var(--brand-secondary); }
    </style>
</head>
<body class="font-sans antialiased text-slate-900 bg-slate-50 h-full flex flex-col">

    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                @if($hotel && $hotel->logo_path)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($hotel->logo_path) }}" alt="{{ $hotel->name }}" class="h-10 w-auto rounded">
                @else
                    <div class="w-10 h-10 rounded bg-brand-primary flex items-center justify-center text-white font-bold text-xl">
                        {{ strtoupper(substr($hotel->name ?? 'H', 0, 1)) }}
                    </div>
                @endif
                <span class="font-bold text-xl text-slate-800">{{ $hotel->name ?? 'Hotel Booking' }}</span>
            </div>
        </div>
    </header>

    <main class="flex-grow">
        {{ $slot }}
    </main>

    <footer class="bg-white border-t border-slate-200 mt-auto py-8 text-center">
        <div class="max-w-5xl mx-auto px-4 space-y-2">
            <p class="text-sm text-slate-500">&copy; {{ date('Y') }} {{ $hotel->name ?? 'TalleenPMS' }}. All rights reserved.</p>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                Powered by <span class="text-blue-600">TALLEEN PMS</span>
            </p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
