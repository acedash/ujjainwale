<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function reviews()
    {
        $reviews = auth()->user()->reviews()->with('business')->latest()->get();
        return view('dashboard.reviews', compact('reviews'));
    }

    public function businesses()
    {
        $businesses = auth()->user()->businesses()->with(['categories', 'neighborhoods'])->latest()->get();
        return view('dashboard.businesses', compact('businesses'));
    }

    public function editBusiness(\App\Models\Business $business)
    {
        if ($business->owner_id !== auth()->id()) {
            abort(403);
        }
        return view('dashboard.edit-business', compact('business'));
    }
}
