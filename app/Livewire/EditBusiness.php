<?php

namespace App\Livewire;

use App\Models\Business;
use Livewire\Component;

class EditBusiness extends Component
{
    use \Livewire\WithFileUploads;

    public Business $business;

    public $description = '';
    public $address = '';
    public $lat = null;
    public $lng = null;
    public $phone = '';
    public $website = '';
    public $success = false;

    // Media Uploads
    public $newLogo;
    public $newCover;
    public $newGallery = [];

    // Relationships
    public $category_ids = [];
    public $neighborhood_ids = [];

    public function mount(Business $business)
    {
        $this->business = $business;

        $this->description = $business->description;
        $this->address = $business->address;
        $this->lat = $business->lat;
        $this->lng = $business->lng;
        $this->phone = $business->phone;
        $this->website = $business->website;

        $this->category_ids = $business->categories->pluck('id')->toArray();
        $this->neighborhood_ids = $business->neighborhoods->pluck('id')->toArray();
    }

    protected $rules = [
        'description' => 'required|string|max:1000',
        'address' => 'required|string|max:255',
        'lat' => 'required|numeric',
        'lng' => 'required|numeric',
        'phone' => 'nullable|string|max:50',
        'website' => 'nullable|url|max:255',
        'category_ids' => 'required|array|min:1',
        'category_ids.*' => 'exists:categories,id',
        'neighborhood_ids' => 'required|array|min:1',
        'neighborhood_ids.*' => 'exists:neighborhoods,id',
        'newLogo' => 'nullable|image|max:2048',
        'newCover' => 'nullable|image|max:5120',
        'newGallery.*' => 'image|max:2048',
    ];

    public function submit()
    {
        $this->validate();

        $this->business->update([
            'description' => $this->description,
            'address' => $this->address,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'phone' => $this->phone,
            'website' => $this->website,
        ]);

        $this->business->categories()->sync($this->category_ids);
        $this->business->neighborhoods()->sync($this->neighborhood_ids);

        if ($this->newLogo) {
            $this->business->clearMediaCollection('logo');
            $this->business->addMedia($this->newLogo)->toMediaCollection('logo');
        }

        if ($this->newCover) {
            $this->business->clearMediaCollection('cover');
            $this->business->addMedia($this->newCover)->toMediaCollection('cover');
        }

        if (!empty($this->newGallery)) {
            foreach ($this->newGallery as $image) {
                $this->business->addMedia($image)->toMediaCollection('gallery');
            }
        }

        $this->success = true;
        $this->reset(['newLogo', 'newCover', 'newGallery']);
        
        // Refresh the model so fresh media loads
        $this->business->refresh();
    }

    public function deleteGalleryImage($mediaId)
    {
        $media = $this->business->getMedia('gallery')->firstWhere('id', $mediaId);
        if ($media) {
            $media->delete();
            $this->business->refresh();
        }
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

    public function toJSON()
    {
        return [];
    }

    public function render()
    {
        return view('livewire.edit-business');
    }
}
