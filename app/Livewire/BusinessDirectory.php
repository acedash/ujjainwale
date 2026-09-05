<?php

namespace App\Livewire;

use App\Models\Business;
use App\Models\Category;
use App\Models\Neighborhood;
use Livewire\Component;
use Livewire\WithPagination;

class BusinessDirectory extends Component
{
    use WithPagination;

    public $search = '';

    public $category = '';

    public $neighborhood = '';

    public $showMapOnMobile = false;

    // Use query string to keep URL in sync
    protected $queryString = ['search', 'category', 'neighborhood'];

    public function updating($name, $value)
    {
        if (in_array($name, ['search', 'category', 'neighborhood'])) {
            $this->resetPage();
        }
    }

    public function toggleMobileMap()
    {
        $this->showMapOnMobile = ! $this->showMapOnMobile;
    }

    public function render()
    {
        $query = Business::with(['categories', 'neighborhoods'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->where('status', '!=', 'pending_review');

        if (! empty($this->search)) {
            $query->where('name', 'like', '%'.$this->search.'%');
        }

        if (! empty($this->category)) {
            $query->whereHas('categories', function ($q) {
                $q->where('categories.id', $this->category);
            });
        }

        if (! empty($this->neighborhood)) {
            $query->whereHas('neighborhoods', function ($q) {
                $q->where('neighborhoods.id', $this->neighborhood);
            });
        }

        $businesses = $query->paginate(20);

        // Prepare map data (all matching results up to a reasonable limit, e.g., 200, for map markers)
        // We don't paginate map markers to ensure users see all relevant points on the map.
        $mapQuery = clone $query;
        $mapBusinesses = $mapQuery->limit(200)->get(['id', 'name', 'address', 'lat', 'lng', 'slug'])->map(function ($b) {
            return [
                'id' => $b->id,
                'name' => $b->name,
                'address' => $b->address,
                'lat' => $b->lat,
                'lng' => $b->lng,
                'url' => route('directory.show', $b->slug),
            ];
        });

        // Emit event to update map markers
        $this->dispatch('update-markers', markers: $mapBusinesses);

        return view('livewire.business-directory', [
            'businesses' => $businesses,
            'mapBusinesses' => $mapBusinesses,
            'categories' => Category::whereIn('type', ['business', 'both'])->orderBy('name')->get(),
            'neighborhoods' => Neighborhood::orderBy('name')->get(),
        ])->layout('layouts.app');
    }
}
