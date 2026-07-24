<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Talleen PMS</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            900: '#0f172a', // very dark blue for left panel
                            800: '#1e293b', // slightly lighter for cards
                        },
                        accent: '#6366f1' // purple/indigo
                    }
                }
            }
        }
    </script>
</head>
<body class="antialiased text-gray-900 bg-white font-sans flex min-h-screen selection:bg-brand-500 selection:text-white">

    <!-- Left Side: Branding & Features -->
    <div class="hidden lg:flex flex-col w-1/2 bg-brand-900 text-white p-12 relative overflow-hidden">
        
        <!-- Background Gradient glow -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
            <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-brand-700/30 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-[-20%] left-[-10%] w-[600px] h-[600px] bg-accent/20 rounded-full blur-[120px]"></div>
        </div>

        <div class="relative z-10 flex-1 flex flex-col">
            <!-- Logo -->
            <div class="flex items-center gap-3 mb-16">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-accent flex items-center justify-center shadow-lg">
                    <span class="text-xl font-bold text-white">T</span>
                </div>
                <div>
                    <div class="text-xl font-bold tracking-tight">Talleen PMS</div>
                    <div class="text-xs text-brand-100/70 font-medium tracking-wide">Enterprise Hotel Platform</div>
                </div>
            </div>

            <!-- Hero Text -->
            <h1 class="text-4xl xl:text-5xl font-bold leading-[1.15] mb-6 tracking-tight">
                The Complete <br/>
                <span class="text-accent font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-accent">Property Ecosystem</span> <br/>
                for Modern Hotels
            </h1>
            <p class="text-lg text-brand-100/80 leading-relaxed mb-10 max-w-lg">
                From booking to checkout — manage your entire hotel with automated tools, real-time analytics, and full channel integration.
            </p>

            <!-- Stats Grid -->
            <div class="grid grid-cols-4 gap-4 mb-10 max-w-xl">
                <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center backdrop-blur-md">
                    <div class="text-2xl font-bold text-white mb-1">15+</div>
                    <div class="text-xs text-brand-100/60 font-medium">Modules</div>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center backdrop-blur-md">
                    <div class="text-2xl font-bold text-white mb-1">9</div>
                    <div class="text-xs text-brand-100/60 font-medium">User Roles</div>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center backdrop-blur-md">
                    <div class="text-2xl font-bold text-white mb-1">11</div>
                    <div class="text-xs text-brand-100/60 font-medium">Property Types</div>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center backdrop-blur-md">
                    <div class="text-2xl font-bold text-white mb-1">10+</div>
                    <div class="text-xs text-brand-100/60 font-medium">Integrations</div>
                </div>
            </div>

            <!-- Platform Highlights -->
            <div>
                <div class="text-xs font-semibold text-brand-100/50 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    Platform Highlights
                </div>
                <div class="bg-white/5 border border-white/10 rounded-2xl p-5 backdrop-blur-md flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-accent/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <div>
                        <div class="font-semibold text-white">Automated Billing</div>
                        <div class="text-sm text-brand-100/60 mt-1">Smart invoicing, tax calculations, and dynamic pricing rules.</div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Right Side: Login Form -->
    <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 sm:px-16 md:px-24 xl:px-32 relative">
        
        <div class="w-full max-w-sm mx-auto">
            
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 text-brand-600 text-xs font-semibold mb-8">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                Tenant Portal
            </div>

            <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome back</h2>
            <p class="text-gray-500 mb-8 text-sm">Sign in to access your hotel's platform</p>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                
                <h3 class="text-sm font-semibold text-gray-700">Sign in with your account</h3>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-medium text-gray-600 mb-1">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-colors text-sm" placeholder="your@hotel.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-xs font-medium text-gray-600">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-brand-600 hover:text-brand-700 font-medium">Forgot password?</a>
                        @endif
                    </div>
                    <input id="password" type="password" name="password" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-colors text-sm" placeholder="Enter password">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                
                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-brand-600 bg-gray-50 border-gray-300 rounded focus:ring-brand-500 focus:ring-2">
                    <label for="remember_me" class="ml-2 text-sm font-medium text-gray-600">Remember me</label>
                </div>

                <!-- Submit -->
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-brand-600 hover:bg-brand-700 text-white font-medium rounded-lg shadow-[0_4px_14px_0_rgba(37,99,235,0.39)] transition-all hover:shadow-[0_6px_20px_rgba(37,99,235,0.23)] hover:-translate-y-0.5 active:translate-y-0">
                    Sign In to Talleen PMS
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </button>
            </form>

            <!-- What you get access to -->
            <div class="mt-10 p-5 rounded-xl bg-gray-50 border border-gray-100">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">What you get access to</div>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="text-sm text-gray-600">Property management & tenant portal</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="text-sm text-gray-600">Bookings, billing, & reports in one place</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="text-sm text-gray-600">Smart insights & automated compliance</span>
                    </li>
                </ul>
            </div>

            <!-- Footer -->
            <div class="mt-12 text-center pb-8 lg:pb-0">
                <div class="text-xs text-gray-400 mb-4">Trusted by hotels across regions</div>
                <div class="flex flex-wrap justify-center gap-2">
                    <span class="px-3 py-1 bg-gray-50 border border-gray-200 rounded-full text-xs text-gray-500 font-medium">Boutique</span>
                    <span class="px-3 py-1 bg-gray-50 border border-gray-200 rounded-full text-xs text-gray-500 font-medium">Resorts</span>
                    <span class="px-3 py-1 bg-gray-50 border border-gray-200 rounded-full text-xs text-gray-500 font-medium">Chains</span>
                    <span class="px-3 py-1 bg-gray-50 border border-gray-200 rounded-full text-xs text-gray-500 font-medium">Apartments</span>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
