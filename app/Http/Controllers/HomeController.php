<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Article;
use App\Models\Business;
use App\Models\Category;
use App\Models\ExternalHeadline;
use App\Models\Neighborhood;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Hero Carousel Items (Max 4 featured articles/headlines)
        $featuredArticles = Article::with(['categories', 'author'])
            ->published()
            ->where('is_featured', true)
            ->latest('published_at')
            ->take(4)
            ->get();

        $featuredExternal = ExternalHeadline::with(['categories', 'newsSource'])
            ->where('is_featured', true)
            ->latest('published_at')
            ->take(2)
            ->get();

        $heroItems = $featuredArticles->concat($featuredExternal)->sortByDesc('published_at')->take(4)->values();

        if ($heroItems->count() < 4) {
            $existingArticleIds = $heroItems->whereInstanceOf(Article::class)->pluck('id')->toArray();
            $existingExternalIds = $heroItems->whereInstanceOf(ExternalHeadline::class)->pluck('id')->toArray();

            $fillExternal = ExternalHeadline::with(['categories', 'newsSource'])
                ->whereNotIn('id', $existingExternalIds)
                ->whereNotNull('thumbnail_url')
                ->latest('published_at')
                ->take(4 - $heroItems->count())
                ->get();
            $heroItems = $heroItems->concat($fillExternal)->values();

            if ($heroItems->count() < 4) {
                $fillArticles = Article::with(['categories', 'author'])
                    ->published()
                    ->whereNotIn('id', $existingArticleIds)
                    ->latest('published_at')
                    ->take(4 - $heroItems->count())
                    ->get();
                $heroItems = $heroItems->concat($fillArticles)->values();
            }
        }

        $usedArticleIds = $heroItems->whereInstanceOf(Article::class)->pluck('id')->toArray();
        $usedExternalIds = $heroItems->whereInstanceOf(ExternalHeadline::class)->pluck('id')->toArray();

        // 2. Breaking News (Curated top 4 urgent/latest stories)
        $breakingNews = Article::with(['categories', 'author'])
            ->published()
            ->whereNotIn('id', $usedArticleIds)
            ->latest('published_at')
            ->take(4)
            ->get();

        if ($breakingNews->count() < 4) {
            $fillBreaking = ExternalHeadline::with(['categories', 'newsSource'])
                ->whereNotIn('id', $usedExternalIds)
                ->latest('published_at')
                ->take(4 - $breakingNews->count())
                ->get();
            $breakingNews = $breakingNews->concat($fillBreaking)->sortByDesc('published_at')->values();
        }

        $usedArticleIds = array_merge($usedArticleIds, $breakingNews->whereInstanceOf(Article::class)->pluck('id')->toArray());
        $usedExternalIds = array_merge($usedExternalIds, $breakingNews->whereInstanceOf(ExternalHeadline::class)->pluck('id')->toArray());

        // 3. Category 1, Category 2, Category 3 (Curated top editorial & local categories)
        $categories = Category::whereIn('type', ['news', 'both'])
            ->whereNotIn('slug', [
                'makhapashhatha',
                'vashashhasahatayahatha-lghakathae',
                'business',
                'civic',
                'sports',
                'food',
            ])
            ->with([
                'articles' => function ($query) use ($usedArticleIds) {
                    $query->published()
                        ->whereNotIn('articles.id', $usedArticleIds)
                        ->latest('published_at')
                        ->limit(6)
                        ->with(['categories', 'author']);
                },
                'externalHeadlines' => function ($query) use ($usedExternalIds) {
                    $query->whereNotIn('external_headlines.id', $usedExternalIds)
                        ->latest('published_at')
                        ->limit(6)
                        ->with(['categories', 'newsSource']);
                },
            ])
            ->get();

        foreach ($categories as $cat) {
            $cat->feedItems = $cat->articles->concat($cat->externalHeadlines)
                ->sortByDesc(fn ($item) => $item->published_at ?? $item->created_at)
                ->take(4)
                ->values();
        }

        $activeCategories = $categories
            ->filter(fn ($cat) => $cat->feedItems->count() >= 2)
            ->sortByDesc(fn ($cat) => $cat->feedItems->count())
            ->values();

        $category1 = $activeCategories->get(0);
        $category2 = $activeCategories->get(1);
        $category3 = $activeCategories->get(2);

        // 4. Yellow Pages / Local Businesses Showcase
        $featuredBusinesses = Business::with(['categories', 'neighborhoods'])
            ->where('status', '!=', 'pending_review')
            ->latest()
            ->take(4)
            ->get();

        // 5. Top ticker, neighborhoods & latest external headlines
        $neighborhoods = Neighborhood::orderBy('name')->get();
        $latestHeadlines = ExternalHeadline::with(['categories', 'newsSource'])
            ->latest('published_at')
            ->take(6)
            ->get();

        // 6. Admin-managed advertisements
        $advertisements = Advertisement::with('media')
            ->active()
            ->orderBy('order')
            ->get()
            ->groupBy('placement');

        return view('welcome', compact(
            'heroItems',
            'breakingNews',
            'category1',
            'category2',
            'category3',
            'featuredBusinesses',
            'neighborhoods',
            'latestHeadlines',
            'advertisements'
        ));
    }
}
