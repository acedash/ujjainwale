<?php

namespace App\Http\Controllers;

use App\Models\ExternalHeadline;

class ExternalHeadlineController extends Controller
{
    public function index()
    {
        $headlines = ExternalHeadline::with('newsSource')->latest('published_at')->paginate(20);
        return view('external.index', compact('headlines'));
    }

    public function show(ExternalHeadline $headline)
    {
        return view('external.show', compact('headline'));
    }
}
