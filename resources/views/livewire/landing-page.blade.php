<div>
    {{-- Hero Section --}}
    <div class="relative h-[80vh] min-h-[600px] flex items-center justify-center overflow-hidden bg-slate-900">
        {{-- Background Image (use first gallery image or fallback) --}}
        @php
            $bgImage = !empty($website->gallery_images) ? Storage::url($website->gallery_images[0]) : 'https://images.unsplash.com/photo-1542314831-c6a4d14d885?q=80&w=2000&auto=format&fit=crop';
        @endphp
        <img src="{{ $bgImage }}" class="absolute inset-0 w-full h-full object-cover opacity-60">
        
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>

        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto animate-in fade-in slide-in-from-bottom-8 duration-1000">
            <h1 class="text-5xl md:text-7xl font-black text-white tracking-tight mb-6">{{ $website->hero_title }}</h1>
            @if($website->hero_subtitle)
                <p class="text-xl md:text-2xl text-slate-200 font-medium mb-10 max-w-2xl mx-auto">{{ $website->hero_subtitle }}</p>
            @endif
            
            <a href="{{ route('book', $hotel->subdomain) }}" class="inline-flex items-center gap-2 bg-brand-primary hover:bg-brand-primary/90 text-white font-black text-lg px-8 py-4 rounded-full shadow-2xl transition hover:scale-105">
                Book Your Stay Now
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>

    {{-- About & Video Section --}}
    <div class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-slate-900 mb-6">Welcome to {{ $hotel->name }}</h2>
                    <div class="prose prose-lg text-slate-600">
                        {!! nl2br(e($website->about_text ?? 'Experience luxury and comfort like never before. Welcome to our hotel.')) !!}
                    </div>
                </div>
                
                @if($website->video_url)
                    <div class="rounded-3xl overflow-hidden shadow-2xl aspect-video bg-slate-100 relative group">
                        {{-- Super simple fallback for video embeds. For production, a proper YouTube/Vimeo ID parser is better --}}
                        @php
                            $embedUrl = str_replace('watch?v=', 'embed/', $website->video_url);
                        @endphp
                        <iframe src="{{ $embedUrl }}" class="absolute inset-0 w-full h-full border-0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Photo Gallery --}}
    @if(!empty($website->gallery_images) && count($website->gallery_images) > 1)
    <div class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-slate-900 mb-12 text-center">Gallery</h2>
            <div class="columns-1 sm:columns-2 md:columns-3 gap-6 space-y-6">
                @foreach(array_slice($website->gallery_images, 1) as $image)
                    <div class="break-inside-avoid rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition duration-300">
                        <img src="{{ Storage::url($image) }}" class="w-full h-auto transform hover:scale-105 transition duration-500">
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Google Maps & Reviews --}}
    @if($website->google_map_embed || $website->google_reviews_embed)
    <div class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 {{ $website->google_map_embed && $website->google_reviews_embed ? 'lg:grid-cols-2' : '' }} gap-12">
                @if($website->google_map_embed)
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <svg class="w-6 h-6 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Our Location
                        </h2>
                        <div class="rounded-3xl overflow-hidden shadow-lg h-[400px] bg-slate-100">
                            {!! $website->google_map_embed !!}
                        </div>
                    </div>
                @endif
                
                @if($website->google_reviews_embed)
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <svg class="w-6 h-6 text-brand-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            Guest Reviews
                        </h2>
                        <div class="rounded-3xl p-6 bg-slate-50 border border-slate-200 h-[400px] overflow-y-auto">
                            {!! $website->google_reviews_embed !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- Call to Action & Socials --}}
    <div class="bg-brand-primary text-white py-20 text-center">
        <h2 class="text-4xl font-black mb-8">Ready to escape?</h2>
        <a href="{{ route('book', $hotel->subdomain) }}" class="inline-block bg-white text-brand-primary font-black text-lg px-8 py-4 rounded-full shadow-xl transition hover:scale-105 mb-12">
            Check Availability
        </a>
        
        <div class="flex items-center justify-center gap-6">
            @if($website->facebook_url)
                <a href="{{ $website->facebook_url }}" target="_blank" class="w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
            @endif
            @if($website->instagram_url)
                <a href="{{ $website->instagram_url }}" target="_blank" class="w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                </a>
            @endif
            @if($website->twitter_url)
                <a href="{{ $website->twitter_url }}" target="_blank" class="w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                </a>
            @endif
        </div>
    </div>
</div>
