<?php

namespace App\Livewire\Admin;

use App\Models\HotelWebsite;
use Livewire\Component;
use Livewire\WithFileUploads;

class WebsiteBuilder extends Component
{
    use WithFileUploads;

    public $hero_title;
    public $hero_subtitle;
    public $about_text;
    public $video_url;
    public $facebook_url;
    public $instagram_url;
    public $twitter_url;
    public $google_map_embed;
    public $google_reviews_embed;
    public $is_published = false;

    public $new_gallery_images = [];
    public $existing_gallery_images = [];

    public function mount()
    {
        $hotel = auth()->user()->hotel;
        $website = $hotel->website;

        if ($website) {
            $this->hero_title = $website->hero_title;
            $this->hero_subtitle = $website->hero_subtitle;
            $this->about_text = $website->about_text;
            $this->video_url = $website->video_url;
            $this->facebook_url = $website->facebook_url;
            $this->instagram_url = $website->instagram_url;
            $this->twitter_url = $website->twitter_url;
            $this->google_map_embed = $website->google_map_embed;
            $this->google_reviews_embed = $website->google_reviews_embed;
            $this->is_published = (bool) $website->is_published;
            $this->existing_gallery_images = $website->gallery_images ?? [];
        } else {
            $this->hero_title = "Welcome to " . $hotel->name;
        }
    }

    public function save()
    {
        $this->validate([
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'nullable|string|max:255',
            'about_text' => 'nullable|string',
            'video_url' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'google_map_embed' => 'nullable|string',
            'google_reviews_embed' => 'nullable|string',
            'new_gallery_images.*' => 'image|max:2048', // 2MB max
        ]);

        $hotel = auth()->user()->hotel;
        
        $imagePaths = $this->existing_gallery_images;

        if ($this->new_gallery_images) {
            foreach ($this->new_gallery_images as $image) {
                $path = $image->store('websites/galleries', 'public');
                $imagePaths[] = $path;
            }
        }

        HotelWebsite::updateOrCreate(
            ['hotel_id' => $hotel->id],
            [
                'hero_title' => $this->hero_title,
                'hero_subtitle' => $this->hero_subtitle,
                'about_text' => $this->about_text,
                'video_url' => $this->video_url,
                'facebook_url' => $this->facebook_url,
                'instagram_url' => $this->instagram_url,
                'twitter_url' => $this->twitter_url,
                'google_map_embed' => $this->google_map_embed,
                'google_reviews_embed' => $this->google_reviews_embed,
                'is_published' => $this->is_published,
                'gallery_images' => $imagePaths,
            ]
        );

        $this->existing_gallery_images = $imagePaths;
        $this->new_gallery_images = [];

        session()->flash('success', 'Website settings saved successfully.');
    }

    public function removeImage($index)
    {
        if (isset($this->existing_gallery_images[$index])) {
            unset($this->existing_gallery_images[$index]);
            $this->existing_gallery_images = array_values($this->existing_gallery_images); // reindex
            
            $hotel = auth()->user()->hotel;
            if ($hotel->website) {
                $hotel->website->update(['gallery_images' => $this->existing_gallery_images]);
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.website-builder');
    }
}
