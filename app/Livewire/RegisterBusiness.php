<?php

namespace App\Livewire;

use App\Models\Business;
use Illuminate\Support\Str;
use Livewire\Component;

class RegisterBusiness extends Component
{
    use \Livewire\WithFileUploads;

    public $name = '';
    public $description = '';
    public $address = '';
    public $lat = null;
    public $lng = null;
    public $phone = '';
    public $website = '';
    public $honeypot = '';
    public $success = false;

    // Media
    public $logo;
    public $cover;
    public $gallery = [];

    // New relationships
    public $category_ids = [];
    public $neighborhood_ids = [];

    public function mount()
    {
        // Set default coordinates for Indore
        $this->lat = 22.7196;
        $this->lng = 75.8577;
    }

    public $user_name = '';
    public $user_email = '';
    public $user_password = '';
    public $user_password_confirmation = '';

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'address' => 'required|string|max:255',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'neighborhood_ids' => 'nullable|array',
            'neighborhood_ids.*' => 'exists:neighborhoods,id',
            'logo' => 'nullable|image|max:2048', // 2MB max
            'cover' => 'nullable|image|max:5120', // 5MB max
            'gallery.*' => 'image|max:2048',
        ];

        if (auth()->guest()) {
            $rules['user_name'] = ['required', 'string', 'max:255'];
            $rules['user_email'] = ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:\App\Models\User,email'];
            $rules['user_password'] = ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'user_email.unique' => 'Looks like you already have an account — <a href="'.route('login').'" class="underline font-bold text-city-accent hover:text-ink">log in instead</a> to register this business.',
        ];
    }

    public function submit()
    {
        if (! empty($this->honeypot)) {
            return;
        }

        $this->validate();

        $owner_id = auth()->id();

        if (auth()->guest()) {
            $user = \App\Models\User::create([
                'name' => $this->user_name,
                'email' => $this->user_email,
                'password' => \Illuminate\Support\Facades\Hash::make($this->user_password),
            ]);

            event(new \Illuminate\Auth\Events\Registered($user));
            auth()->login($user);
            $owner_id = $user->id;
        }

        $business = Business::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name).'-'.uniqid(),
            'description' => $this->description,
            'address' => $this->address,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'phone' => $this->phone,
            'website' => $this->website,
            'status' => 'pending_review',
            'owner_id' => $owner_id,
        ]);

        $business->categories()->attach($this->category_ids);
        $business->neighborhoods()->attach($this->neighborhood_ids);

        if ($this->logo) {
            $business->addMedia($this->logo)->toMediaCollection('logo');
        }

        if ($this->cover) {
            $business->addMedia($this->cover)->toMediaCollection('cover');
        }

        foreach ($this->gallery as $image) {
            $business->addMedia($image)->toMediaCollection('gallery');
        }

        $this->success = true;
        $this->reset(['name', 'description', 'address', 'lat', 'lng', 'phone', 'website', 'category_ids', 'neighborhood_ids', 'honeypot', 'logo', 'cover', 'gallery', 'user_name', 'user_email', 'user_password', 'user_password_confirmation']);
    }

    #[\Livewire\Attributes\Computed]
    public function availableCategories()
    {
        return \App\Models\Category::orderBy('name')->get();
    }

    #[\Livewire\Attributes\Computed]
    public function availableNeighborhoods()
    {
        return \App\Models\Neighborhood::orderBy('name')->get();
    }

    // Absorb extraneous JS Proxy calls from browser extensions trying to serialize $wire
    public function toJSON()
    {
        return [];
    }

    public function render()
    {
        return view('livewire.register-business')->layout('layouts.app');
    }
}
