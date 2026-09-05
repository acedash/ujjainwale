<?php

namespace App\Http\Controllers;

use App\Models\Business;

class BusinessController extends Controller
{
    public function show(Business $business)
    {
        $business->load(['categories', 'neighborhoods', 'reviews' => function ($q) {
            $q->where('is_approved', true)->with('user')->latest();
        }]);

        if ($business->status === 'pending_review' && (! auth()->check() || (! auth()->user()->hasRole(['Admin', 'Editor']) && auth()->id() !== $business->owner_id))) {
            abort(404);
        }

        $articles = $business->mentionedInArticles()->with(['author', 'categories', 'neighborhoods'])->latest('published_at')->get();

        $reviews = $business->reviews()->where('is_approved', true)->with('user')->latest()->get();

        return view('businesses.show', compact('business', 'articles', 'reviews'));
    }
}
