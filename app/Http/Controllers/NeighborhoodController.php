<?php

namespace App\Http\Controllers;

use App\Models\Neighborhood;

class NeighborhoodController extends Controller
{
    public function show(Neighborhood $neighborhood)
    {
        $articles = $neighborhood->articles()->published()->with(['author', 'categories', 'neighborhoods'])->latest('published_at')->paginate(12);
        $businesses = $neighborhood->businesses()->with(['categories', 'neighborhoods'])->latest()->limit(10)->get();

        return view('neighborhoods.show', compact('neighborhood', 'articles', 'businesses'));
    }
}
