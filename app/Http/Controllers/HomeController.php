<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $featuredArticles = Article::with(['categories', 'author'])
            ->published()
            ->where('is_featured', true)
            ->latest('published_at')
            ->take(4)
            ->get();

        $featuredExternal = \App\Models\ExternalHeadline::with(['categories', 'newsSource'])
            ->where('is_featured', true)
            ->latest('published_at')
            ->take(1) // Cap external to 1 in Hero
            ->get();
            
        $featuredItems = $featuredArticles->concat($featuredExternal)->sortByDesc('published_at')->take(4)->values();

        $remainingSlots = 4 - $featuredItems->count();
        
        $heroItems = collect($featuredItems);

        if ($remainingSlots > 0) {
            $featuredArticleIds = $featuredItems->whereInstanceOf(Article::class)->pluck('id')->toArray();
            $featuredExternalIds = $featuredItems->whereInstanceOf(\App\Models\ExternalHeadline::class)->pluck('id')->toArray();

            $latestArticles = Article::with(['categories', 'author'])
                ->published()
                ->whereNotIn('id', $featuredArticleIds)
                ->latest('published_at')
                ->take($remainingSlots)
                ->get();

            // If we already have 1 external in the hero, don't fetch any more
            $latestExternal = collect();
            if (empty($featuredExternalIds)) {
                $latestExternal = \App\Models\ExternalHeadline::with(['categories', 'newsSource'])
                    ->latest('published_at')
                    ->take(1) // Cap to 1
                    ->get();
            }

            $latestItems = $latestArticles->concat($latestExternal)->sortByDesc('published_at')->take($remainingSlots)->values();
            $heroItems = $heroItems->concat($latestItems);
        }
        
        $heroArticleIds = $heroItems->whereInstanceOf(Article::class)->pluck('id')->toArray();
        $heroExternalIds = $heroItems->whereInstanceOf(\App\Models\ExternalHeadline::class)->pluck('id')->toArray();

        // Secondary column items (3 slots)
        $secondaryArticles = Article::with(['categories', 'author'])
            ->published()
            ->whereNotIn('id', $heroArticleIds)
            ->latest('published_at')
            ->take(3)
            ->get();

        $secondaryExternal = \App\Models\ExternalHeadline::with(['categories', 'newsSource'])
            ->whereNotIn('id', $heroExternalIds)
            ->latest('published_at')
            ->take(1) // Cap to 1 in Latest Updates
            ->get();

        $secondaryItems = $secondaryArticles->concat($secondaryExternal)->sortByDesc('published_at')->take(3)->values();

        $excludeArticleIds = collect($heroArticleIds)->merge($secondaryItems->whereInstanceOf(Article::class)->pluck('id'))->unique()->toArray();
        $excludeExternalIds = collect($heroExternalIds)->merge($secondaryItems->whereInstanceOf(\App\Models\ExternalHeadline::class)->pluck('id'))->unique()->toArray();

        // Get categories that have articles or external headlines
        $categories = Category::whereIn('type', ['news', 'both'])
            ->with(['articles' => function ($query) use ($excludeArticleIds) {
                $query->published()
                      ->whereNotIn('articles.id', $excludeArticleIds)
                      ->latest('published_at')
                      ->limit(4)
                      ->with('categories', 'author');
            }, 'externalHeadlines' => function ($query) use ($excludeExternalIds) {
                $query->whereNotIn('external_headlines.id', $excludeExternalIds)
                      ->latest('published_at')
                      ->limit(4)
                      ->with('categories', 'newsSource');
            }])
            ->get()
            ->map(function ($cat) {
                // Ensure balanced categories if needed, for now just sort and take 4
                $cat->mixed_items = $cat->articles->concat($cat->externalHeadlines)
                    ->sortByDesc('published_at')
                    ->take(4)
                    ->values();
                return $cat;
            })
            ->filter(fn ($cat) => $cat->mixed_items->count() > 0);

        $neighborhoods = \App\Models\Neighborhood::orderBy('name')->get();

        $featuredBusinesses = \App\Models\Business::where('is_featured', true)
            ->latest()
            ->take(4)
            ->get();

        // Used by Trending Elsewhere section (which is explicitly external)
        $externalHeadlines = \App\Models\ExternalHeadline::with('newsSource')
            ->whereNotIn('id', $excludeExternalIds)
            ->latest('published_at')
            ->take(8)
            ->get();

        return view('welcome', compact('heroItems', 'secondaryItems', 'categories', 'neighborhoods', 'featuredBusinesses', 'externalHeadlines'));
    }
}
