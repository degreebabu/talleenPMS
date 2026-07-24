<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class AiAssistant extends Component
{
    public $isOpen = false;
    public $messages = [];
    public $userInput = '';

    public function mount()
    {
        $this->messages = [
            ['role' => 'assistant', 'content' => 'Hello! I am your AI PMS Assistant. How can I help you today?']
        ];
    }

    public function toggle()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function sendMessage()
    {
        $this->validate([
            'userInput' => 'required|string|max:1000'
        ]);

        // Add user message
        $this->messages[] = ['role' => 'user', 'content' => $this->userInput];
        $query = $this->userInput;
        $this->userInput = '';

        // Contextual data
        $hotelId = auth()->user()->hotel_id;
        $totalBookings = Booking::where('hotel_id', $hotelId)->where('status', 'checked_in')->count();
        $totalRooms = Room::where('hotel_id', $hotelId)->count();
        $context = "You are an AI assistant for a hotel PMS. Context: The hotel currently has {$totalBookings} occupied rooms out of {$totalRooms} total rooms. Answer concisely and professionally.";

        // Call LLM (Using Google Gemini API as an example, but safely falling back if key is missing)
        $apiKey = env('GEMINI_API_KEY');
        
        if (!$apiKey) {
            // Simulated response
            $this->messages[] = ['role' => 'assistant', 'content' => "I am currently running in simulation mode because an API key is missing. However, based on the PMS data, you currently have {$totalBookings} rooms occupied."];
            return;
        }

        try {
            $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $context . "\n\nUser Question: " . $query]]]
                ]
            ]);

            if ($response->successful()) {
                $text = $response->json('candidates.0.content.parts.0.text') ?? 'I could not process that request.';
                $this->messages[] = ['role' => 'assistant', 'content' => $text];
            } else {
                $this->messages[] = ['role' => 'assistant', 'content' => 'Error communicating with AI service.'];
            }
        } catch (\Exception $e) {
            $this->messages[] = ['role' => 'assistant', 'content' => 'Connection error: ' . $e->getMessage()];
        }
    }

    public function render()
    {
        return view('livewire.admin.ai-assistant');
    }
}
