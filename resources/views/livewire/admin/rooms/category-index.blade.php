@php use Illuminate\Support\Facades\Storage; @endphp
<x-slot name="header">Room Categories</x-slot>

<div>
{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <p class="text-slate-500 text-sm">Define the types of rooms your hotel offers (Deluxe, Suite, etc.)</p>
    <button wire:click="openCreate" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Category
    </button>
</div>

{{-- Categories Grid --}}
@if($categories->count())
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 mb-6">
    @foreach($categories as $cat)
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden hover:border-slate-200 transition group">
        {{-- Image --}}
        @if($cat->images->count() > 0)
        @php $imgPath = $cat->images->first()->image_path; @endphp
        <img src="{{ str_starts_with($imgPath, 'http') ? $imgPath : Storage::url($imgPath) }}" class="w-full h-40 object-cover">
        @else
        <div class="w-full h-40 bg-slate-100 flex items-center justify-center">
            <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
        </div>
        @endif

        <div class="p-4">
            <div class="flex items-start justify-between mb-2">
                <h3 class="font-semibold text-slate-900">{{ $cat->name }}</h3>
                <span class="text-blue-400 font-bold text-sm">₹{{ number_format($cat->base_price) }}<span class="text-slate-500 font-normal">/night</span></span>
            </div>
            <p class="text-slate-500 text-xs mb-3 line-clamp-2">{{ $cat->description ?: 'No description provided.' }}</p>

            <div class="flex items-center gap-3 text-xs text-slate-500 mb-3">
                <span>👤 {{ $cat->max_adults }} Adults</span>
                <span>🧒 {{ $cat->max_children }} Children</span>
                <span>🖼 {{ $cat->images->count() }} Photos</span>
            </div>

            @if($cat->amenities_json)
            <div class="flex flex-wrap gap-1 mb-3">
                @foreach(array_slice($cat->amenities_json, 0, 4) as $amenity)
                    <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">{{ $amenity }}</span>
                @endforeach
                @if(count($cat->amenities_json) > 4)
                    <span class="text-xs bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full">+{{ count($cat->amenities_json) - 4 }} more</span>
                @endif
            </div>
            @endif

            <div class="flex gap-2 pt-2 border-t border-slate-200">
                <button wire:click="openEdit({{ $cat->id }})" class="flex-1 text-xs text-slate-500 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-lg py-1.5 transition">Edit</button>
                <button wire:click="deleteCategory({{ $cat->id }})" wire:confirm="Delete this category? Rooms using it will also be affected." class="text-xs text-red-400 hover:text-slate-900 hover:bg-red-50 hover:text-red-600 bg-slate-100 rounded-lg px-3 py-1.5 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>
{{ $categories->links() }}
@else
<div class="bg-white border border-dashed border-slate-200 rounded-2xl p-16 text-center">
    <svg class="w-12 h-12 text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
    <h3 class="text-slate-900 font-semibold mb-2">No room categories yet</h3>
    <p class="text-slate-500 text-sm mb-4">Start by adding a room type like "Deluxe" or "Suite"</p>
    <button wire:click="openCreate" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition">+ Add First Category</button>
</div>
@endif

{{-- Modal --}}
@if($showModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm" wire:click.self="$set('showModal', false)">
    <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto shadow-2xl"
         x-data x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

        <div class="flex items-center justify-between p-6 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-900">{{ $editingId ? 'Edit' : 'New' }} Room Category</h3>
            <button wire:click="$set('showModal', false)" class="text-slate-500 hover:text-slate-900"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>

        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1.5">Category Name *</label>
                <input wire:model="name" type="text" placeholder="e.g. Deluxe Room" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1.5">Description</label>
                <textarea wire:model="description" rows="3" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" placeholder="Describe the room experience..."></textarea>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1.5">Base Price (₹) *</label>
                    <input wire:model="base_price" type="number" min="0" step="0.01" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('base_price') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1.5">Max Adults</label>
                    <input wire:model="max_adults" type="number" min="1" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1.5">Max Children</label>
                    <input wire:model="max_children" type="number" min="0" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            {{-- Amenities --}}
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1.5">Amenities</label>
                <div class="flex gap-2 mb-2">
                    <input wire:model="newAmenity" type="text" wire:keydown.enter.prevent="addAmenity" placeholder="e.g. Air Conditioning" class="flex-1 bg-slate-100 border border-slate-200 rounded-xl px-4 py-2 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <button wire:click="addAmenity" class="px-3 py-2 bg-slate-700 hover:bg-slate-600 text-slate-900 rounded-xl text-sm transition">Add</button>
                </div>
                @if($amenities)
                <div class="flex flex-wrap gap-1.5">
                    @foreach($amenities as $i => $amenity)
                    <span class="flex items-center gap-1 text-xs bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full">
                        {{ $amenity }}
                        <button wire:click="removeAmenity({{ $i }})" class="text-slate-500 hover:text-red-400 ml-0.5">×</button>
                    </span>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Image upload --}}
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1.5">Photos <span class="text-slate-500">(max 3MB each)</span></label>
                <input wire:model="newImages" type="file" accept="image/*" multiple class="w-full text-slate-500 text-sm file:mr-3 file:px-3 file:py-1.5 file:rounded-lg file:bg-slate-700 file:text-slate-900 file:border-0 file:text-sm hover:file:bg-slate-600 transition">
                <div wire:loading wire:target="newImages" class="mt-2 text-xs text-blue-400">Uploading...</div>
                @error('newImages.*') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex gap-3 p-6 pt-0">
            <button wire:click="$set('showModal', false)" class="flex-1 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium rounded-xl transition">Cancel</button>
            <button wire:click="save" wire:loading.attr="disabled" class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition disabled:opacity-50">
                <span wire:loading.remove>Save Category</span>
                <span wire:loading>Saving...</span>
            </button>
        </div>
    </div>
</div>
@endif
</div>
