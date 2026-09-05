<?php

namespace App\Livewire;

use App\Models\Business;
use Livewire\Component;

class LeaveReview extends Component
{
    public Business $business;

    public $rating = 0;

    public $body = '';

    public $submitted = false;

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'body' => 'required|string|min:10|max:1000',
    ];

    public function submit()
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $this->validate();

        $this->business->reviews()->create([
            'user_id' => auth()->id(),
            'rating' => $this->rating,
            'body' => $this->body,
            'is_approved' => false,
        ]);

        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.leave-review');
    }
}
