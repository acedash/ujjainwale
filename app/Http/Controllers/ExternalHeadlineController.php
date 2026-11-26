<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ExternalHeadline;
use Illuminate\Http\Request;

class ExternalHeadlineController extends Controller
{
    public function index(Request $request)
    {
        $query = ExternalHeadline::with(['newsSource', 'categories'])
            ->latest('published_at');

        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->query('category'));
            });
        }

        $headlines = $query->paginate(21)->withQueryString();

        $categories = Category::has('externalHeadlines')
            ->whereIn('type', ['news', 'both'])
            ->whereNotIn('slug', ['makhapashhatha'])
            ->orderByRaw("slug = 'ujjain-news' DESC, name ASC")
            ->get();

        return view('external.index', compact('headlines', 'categories'));
    }

    public function show(ExternalHeadline $headline)
    {
        $headline->load(['newsSource', 'categories']);

        return view('external.show', compact('headline'));
    }
}
