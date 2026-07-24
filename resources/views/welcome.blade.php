<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Talleen PMS - Hotel & Property Management</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            900: '#0c4a6e',
                        }
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="antialiased bg-zinc-950 text-white font-sans selection:bg-brand-500 selection:text-white overflow-x-hidden">

    <!-- Background Gradient Animations -->
    <div class="fixed inset-0 z-0 flex justify-center items-center overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-brand-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-blob"></div>
        <div class="absolute top-[20%] right-[-10%] w-96 h-96 bg-indigo-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-20%] left-[20%] w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-blob animation-delay-4000"></div>
    </div>

    <div class="relative z-10 flex flex-col min-h-screen">
        
        <!-- Navbar -->
        <header class="sticky top-0 w-full backdrop-blur-lg bg-zinc-950/50 border-b border-white/10 z-50 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-brand-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-white">Talleen<span class="text-brand-500">PMS</span></span>
                </div>

                <!-- Nav Links -->
                <nav class="hidden md:flex items-center gap-8">
                    <!-- Menu removed per request -->
                </nav>

                <!-- Auth Buttons -->
                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-white hover:text-brand-400 transition-colors">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium bg-brand-600 text-white px-5 py-2.5 rounded-full hover:bg-brand-500 transition-all shadow-[0_0_20px_rgba(14,165,233,0.3)] hover:scale-105 active:scale-95">Log in</a>
                        @endauth
                    @endif
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <main class="flex-1 flex flex-col items-center justify-center px-6 pt-20 pb-32 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 mb-8 backdrop-blur-sm">
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-500"></span>
                </span>
                <span class="text-xs font-medium text-zinc-300 tracking-wide uppercase">Introducing Multi-Property Management</span>
            </div>

            <h1 class="max-w-4xl text-5xl md:text-7xl font-bold tracking-tight text-transparent bg-clip-text bg-gradient-to-b from-white to-zinc-400 leading-tight mb-6">
                The Future of <br /> Hospitality Management
            </h1>
            
            <p class="max-w-2xl text-lg md:text-xl text-zinc-400 leading-relaxed mb-10">
                A unified, cloud-based platform to seamlessly manage properties, tenants, bookings, and revenue. Built for modern hotel groups and independent properties alike.
            </p>

            <div class="flex flex-col sm:flex-row items-center gap-4">
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 bg-brand-600 hover:bg-brand-500 text-white font-semibold rounded-full shadow-[0_0_30px_rgba(14,165,233,0.3)] transition-all hover:shadow-[0_0_40px_rgba(14,165,233,0.5)] hover:-translate-y-1">
                    Log in to Portal
                </a>
                <a href="#demo" class="w-full sm:w-auto px-8 py-4 bg-white/5 hover:bg-white/10 text-white font-semibold rounded-full border border-white/10 backdrop-blur-md transition-all hover:-translate-y-1">
                    Book a Demo
                </a>
            </div>
        </main>

        <!-- Feature Grid -->
        <section class="max-w-7xl mx-auto px-6 py-24 border-t border-white/10 w-full" id="features">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="p-8 rounded-3xl bg-zinc-900/50 border border-white/5 backdrop-blur-sm hover:bg-zinc-900 transition-colors group">
                    <div class="w-12 h-12 rounded-2xl bg-brand-500/20 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Multi-Property Groups</h3>
                    <p class="text-zinc-400 text-sm leading-relaxed">Manage your entire portfolio from a single dashboard. Define parent companies, allocate properties, and monitor performance enterprise-wide.</p>
                </div>

                <!-- Feature 2 -->
                <div class="p-8 rounded-3xl bg-zinc-900/50 border border-white/5 backdrop-blur-sm hover:bg-zinc-900 transition-colors group">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-500/20 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Tenant Roles</h3>
                    <p class="text-zinc-400 text-sm leading-relaxed">Granular access control for Group Managers, Property Managers, and Staff. Ensure everyone has exactly the right permissions they need.</p>
                </div>

                <!-- Feature 3 -->
                <div class="p-8 rounded-3xl bg-zinc-900/50 border border-white/5 backdrop-blur-sm hover:bg-zinc-900 transition-colors group">
                    <div class="w-12 h-12 rounded-2xl bg-purple-500/20 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Real-time Analytics</h3>
                    <p class="text-zinc-400 text-sm leading-relaxed">Beautiful, actionable data at your fingertips. Track bookings, revenue, and occupancy across all your properties instantly.</p>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="mt-auto py-8 text-center text-zinc-500 text-sm border-t border-white/5">
            <p>&copy; {{ date('Y') }} Talleen PMS. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>
