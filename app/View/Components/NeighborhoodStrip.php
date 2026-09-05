<?php

namespace App\View\Components;

use App\Models\Neighborhood;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NeighborhoodStrip extends Component
{
    public $neighborhoods;

    public function __construct()
    {
        $this->neighborhoods = Neighborhood::orderBy('name')->get();
    }

    public function render(): View|Closure|string
    {
        return view('components.neighborhood-strip');
    }
}
