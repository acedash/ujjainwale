<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class AcceptInvite extends Component
{
    public User $user;

    public $password = '';

    public $password_confirmation = '';

    public function mount(User $user)
    {
        if (! request()->hasValidSignature()) {
            abort(401, 'This invitation link is invalid or has expired.');
        }

        $this->user = $user;
    }

    public function submit()
    {
        $this->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $this->user->update([
            'password' => Hash::make($this->password),
        ]);

        Auth::login($this->user);

        return redirect()->route('filament.admin.pages.dashboard');
    }

    public function render()
    {
        return view('livewire.accept-invite')->layout('layouts.guest');
    }
}
