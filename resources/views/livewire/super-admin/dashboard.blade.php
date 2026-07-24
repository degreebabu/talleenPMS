<div>
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-900 tracking-wide">Command Center</h2>
        <p class="text-slate-500 mt-1">Platform-wide overview and real-time activity stream.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Tenants -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 relative overflow-hidden shadow-sm hover:shadow-md hover:border-blue-200 transition duration-300 group">
            <div class="flex items-center justify-between relative">
                <div>
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Active Tenants</p>
                    <p class="text-4xl font-bold text-slate-900 mt-2">{{ $totalHotels }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 relative overflow-hidden shadow-sm hover:shadow-md hover:border-emerald-200 transition duration-300 group">
            <div class="flex items-center justify-between relative">
                <div>
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Platform Users</p>
                    <p class="text-4xl font-bold text-slate-900 mt-2">{{ $totalUsers }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
        </div>

        <!-- Platform Revenue -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 relative overflow-hidden shadow-sm hover:shadow-md hover:border-purple-200 transition duration-300 group">
            <div class="flex items-center justify-between relative">
                <div>
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total GMV Generated</p>
                    <p class="text-4xl font-bold text-slate-900 mt-2">₹{{ number_format($totalRevenue) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Insights & Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- AI Health Report -->
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-6 shadow-md text-white relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="text-xl font-bold tracking-wide">AI Platform Insights</h3>
            </div>
            <p class="text-indigo-100 leading-relaxed font-medium">
                {{ $aiInsights }}
            </p>
            <div class="mt-6 flex gap-3">
                <button class="px-4 py-2 bg-white text-indigo-600 rounded-lg text-sm font-bold shadow hover:bg-indigo-50 transition">Generate New Analysis</button>
            </div>
        </div>

        <!-- Tenant Performance Chart -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-slate-900">Top Tenants by GMV</h3>
            </div>
            <div class="h-64 relative">
                <canvas id="tenantChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-900">Live Platform Activity</h3>
            <a href="{{ route('super-admin.activity-logs') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition">View All &rarr;</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($recentLogs as $log)
            <div class="px-6 py-4 flex gap-4 hover:bg-slate-50 transition">
                <div class="mt-1">
                    <div class="w-2.5 h-2.5 rounded-full bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.5)]"></div>
                </div>
                <div>
                    <p class="text-sm text-slate-700 font-medium">
                        @if($log->hotel)
                        <span class="text-blue-600 font-bold">[{{ $log->hotel->name }}]</span>
                        @endif
                        {{ $log->description }}
                    </p>
                    <p class="text-xs text-slate-500 mt-1">{{ $log->created_at->diffForHumans() }} &middot; {{ $log->user->name ?? 'System' }}</p>
                </div>
            </div>
            @empty
            <div class="px-6 py-12 text-center text-slate-500 text-sm">
                No recent activity recorded.
            </div>
            @endforelse
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            const ctx = document.getElementById('tenantChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [{
                            label: 'GMV (₹)',
                            data: {!! json_encode($chartData) !!},
                            backgroundColor: 'rgba(59, 130, 246, 0.5)',
                            borderColor: 'rgb(59, 130, 246)',
                            borderWidth: 2,
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: '#f1f5f9' },
                                ticks: { font: { family: 'Outfit' } }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { family: 'Outfit' } }
                            }
                        },
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</div>
