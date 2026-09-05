<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $articles = $category->articles()->published()->with(['author', 'categories', 'neighborhoods'])->get();
        $external = $category->externalHeadlines()->with(['categories', 'newsSource'])->latest('published_at')->get();
        
        $mixed = $articles->concat($external)->sortByDesc(function($item) {
            return $item->published_at ?? $item->created_at;
        })->values();

        $page = request()->get('page', 1);
        $perPage = 12;
        $paginatedArticles = new \Illuminate\Pagination\LengthAwarePaginator(
            $mixed->forPage($page, $perPage),
            $mixed->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $businesses = $category->businesses()->with(['categories', 'neighborhoods'])->latest()->limit(10)->get();

        return view('categories.show', [
            'category' => $category,
            'articles' => $paginatedArticles,
            'businesses' => $businesses
        ]);
    }
}
