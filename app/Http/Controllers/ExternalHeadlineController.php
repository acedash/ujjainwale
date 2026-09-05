<?php

namespace App\Http\Controllers;

use App\Models\ExternalHeadline;

class ExternalHeadlineController extends Controller
{
    public function show(ExternalHeadline $headline)
    {
        return view('external.show', compact('headline'));
    }
}
