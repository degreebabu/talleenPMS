<div>
    <div class="flex items-center justify-end mb-6">
        <select wire:model.live="period" class="bg-white border border-slate-200 rounded-xl pl-4 pr-10 py-2 text-sm font-semibold focus:ring-2 focus:ring-blue-500">
            <option value="7">Last 7 days</option>
            <option value="30">Last 30 days</option>
            <option value="90">Last 90 days</option>
        </select>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm col-span-2 md:col-span-1">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Revenue</div>
            <div class="text-2xl font-black text-slate-900">₹{{ number_format($totalRevenue, 0) }}</div>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Bookings</div>
            <div class="text-2xl font-black text-slate-900">{{ $totalBookings }}</div>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Occupancy</div>
            <div class="text-2xl font-black {{ $occupancyRate >= 70 ? 'text-emerald-600' : ($occupancyRate >= 40 ? 'text-amber-600' : 'text-red-600') }}">{{ $occupancyRate }}%</div>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">ADR</div>
            <div class="text-2xl font-black text-slate-900">₹{{ number_format($avgDailyRate, 0) }}</div>
        </div>
        <div class="bg-gradient-to-br from-indigo-600 to-purple-700 text-white rounded-2xl p-5 shadow-lg">
            <div class="text-xs font-bold text-indigo-200 uppercase tracking-wider mb-1">RevPAR</div>
            <div class="text-2xl font-black">₹{{ number_format($revPar, 0) }}</div>
        </div>
    </div>

    {{-- Revenue Bar Chart (CSS-based) --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm mb-6">
        <h3 class="text-lg font-bold text-slate-900 mb-5">Daily Revenue (Last 14 Days)</h3>
        @php $maxRevenue = max(collect($dailyRevenue)->pluck('revenue')->toArray() ?: [1]); @endphp
        <div class="flex items-end gap-1 h-40">
            @foreach($dailyRevenue as $day)
            @php $height = $maxRevenue > 0 ? round(($day['revenue'] / $maxRevenue) * 100) : 0; @endphp
            <div class="flex-1 flex flex-col items-center gap-1 group cursor-pointer">
                <div class="relative w-full flex items-end justify-center" style="height: 120px;">
                    <div class="w-full bg-indigo-500 hover:bg-indigo-600 rounded-t-lg transition" style="height: {{ max($height, 4) }}%"></div>
                    <div class="absolute -top-7 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs px-2 py-1 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition">
                        ₹{{ number_format($day['revenue'], 0) }}
                    </div>
                </div>
                <div class="text-xs text-slate-400 truncate w-full text-center">{{ $day['date'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- AI Rate Recommendations --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-900">AI Rate Recommendations</h3>
                <p class="text-xs text-slate-500">TalleenPMS AI has analysed your market and suggests the following rate changes</p>
            </div>
        </div>
        <div class="divide-y divide-slate-100">
            @foreach($recommendations as $rec)
            <div class="px-6 py-4 flex items-center justify-between">
                <div>
                    <div class="font-semibold text-slate-900">{{ $rec['room_type'] }}</div>
                    <div class="text-xs text-slate-500 mt-1">{{ $rec['reason'] }}</div>
                </div>
                <div class="flex items-center gap-6 text-sm">
                    <div class="text-center">
                        <div class="text-xs text-slate-500">Current</div>
                        <div class="font-bold text-slate-700">₹{{ number_format($rec['current_rate'], 0) }}</div>
                    </div>
                    <svg class="w-5 h-5 {{ $rec['recommended_rate'] > $rec['current_rate'] ? 'text-emerald-500' : 'text-amber-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $rec['recommended_rate'] > $rec['current_rate'] ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3' }}"/>
                    </svg>
                    <div class="text-center">
                        <div class="text-xs text-slate-500">Suggested</div>
                        <div class="font-black text-lg {{ $rec['recommended_rate'] > $rec['current_rate'] ? 'text-emerald-600' : 'text-amber-600' }}">₹{{ number_format($rec['recommended_rate'], 0) }}</div>
                    </div>
                    <button class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-xl transition">Apply</button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Rate Rules Management --}}
    <div class="mt-8">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-slate-900">Dynamic Rate Rules</h3>
            <button wire:click="createRule" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl transition">
                + New Rule
            </button>
        </div>

        @if($showRuleForm)
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm mb-6">
            <h4 class="font-bold text-slate-900 mb-4">Create Pricing Rule</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Rule Name</label>
                    <input wire:model="rule_name" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Trigger Type</label>
                    <select wire:model="rule_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="season">Seasonal (Dates)</option>
                        <option value="occupancy">Occupancy Based</option>
                        <option value="package">Package / Promotion</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Room Category (Optional)</label>
                    <select wire:model="room_category_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="">All Categories</option>
                        @foreach($roomCategories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                @if($rule_type === 'season')
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Start Date</label>
                    <input wire:model="start_date" type="date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">End Date</label>
                    <input wire:model="end_date" type="date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                @endif
                
                @if($rule_type === 'occupancy')
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Min Occupancy %</label>
                    <input wire:model="min_occupancy" type="number" min="1" max="100" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                @endif
                
                <div class="{{ $rule_type === 'season' ? '' : 'md:col-start-1 md:col-span-1' }}">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Adjustment Type</label>
                    <select wire:model="adjustment_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="percentage">Percentage (%)</option>
                        <option value="fixed">Fixed Amount (₹)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Adjustment Value</label>
                    <input wire:model="adjustment_value" type="number" step="0.01" placeholder="e.g. 15 for +15% or -10 for -10%" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="mt-4 flex justify-end gap-2">
                <button wire:click="$set('showRuleForm', false)" class="px-4 py-2 bg-slate-100 text-slate-700 font-semibold rounded-xl text-sm hover:bg-slate-200 transition">Cancel</button>
                <button wire:click="saveRule" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-xl text-sm hover:bg-blue-500 transition">Save Rule</button>
            </div>
        </div>
        @endif

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-5 py-4">Rule Name</th>
                        <th class="px-5 py-4">Trigger</th>
                        <th class="px-5 py-4">Adjustment</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($rules as $rule)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-5 py-3 font-semibold text-slate-900">{{ $rule->name }}</td>
                        <td class="px-5 py-3">
                            @if($rule->rule_type === 'season')
                                {{ $rule->start_date->format('M d') }} - {{ $rule->end_date->format('M d') }}
                            @elseif($rule->rule_type === 'occupancy')
                                > {{ $rule->min_occupancy_percent }}% Occupancy
                            @else
                                {{ ucfirst($rule->rule_type) }}
                            @endif
                            @if($rule->roomCategory)
                                <div class="text-xs text-slate-500">Only {{ $rule->roomCategory->name }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-3 font-bold {{ $rule->adjustment_value > 0 ? 'text-emerald-600' : 'text-red-500' }}">
                            {{ $rule->adjustment_value > 0 ? '+' : '' }}{{ rtrim(rtrim($rule->adjustment_value, '0'), '.') }}{{ $rule->adjustment_type === 'percentage' ? '%' : '₹' }}
                        </td>
                        <td class="px-5 py-3">
                            <button wire:click="toggleRule({{ $rule->id }})" class="inline-flex items-center gap-1.5 px-2 py-1 rounded-lg text-xs font-semibold {{ $rule->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }} transition">
                                <span class="w-1.5 h-1.5 rounded-full {{ $rule->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                {{ $rule->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <button wire:click="deleteRule({{ $rule->id }})" class="text-red-500 hover:text-red-700 font-semibold" wire:confirm="Are you sure?">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-slate-400">No active pricing rules. Add a rule above to automate revenue management.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
