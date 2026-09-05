<?php

namespace App\Livewire;

use App\Models\Business;
use Livewire\Component;

class ClaimBusiness extends Component
{
    public Business $business;

    public $showModal = false;

    public $message = '';

    public $success = false;

    public $verification_details = '';

    public function mount(Business $business)
    {
        $this->business = $business;
    }

    public function claim()
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        if ($this->business->owner_id) {
            return;
        }

        $this->validate([
            'verification_details' => 'required|string|min:10|max:1000',
        ]);

        \App\Models\BusinessClaim::create([
            'business_id' => $this->business->id,
            'user_id' => auth()->id(),
            'status' => 'pending',
            'verification_details' => $this->verification_details,
        ]);

        $this->success = true;
    }

    public function render()
    {
        return view('livewire.claim-business');
    }
}
