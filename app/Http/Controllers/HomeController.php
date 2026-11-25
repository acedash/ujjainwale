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
            ->take(1)
            ->get();

        $heroItems = $featuredArticles->concat($featuredExternal)->sortByDesc('published_at')->take(4)->values();

        if ($heroItems->count() < 4) {
            $existingArticleIds = $heroItems->whereInstanceOf(Article::class)->pluck('id')->toArray();
            $fillArticles = Article::with(['categories', 'author'])
                ->published()
                ->whereNotIn('id', $existingArticleIds)
                ->latest('published_at')
                ->take(4 - $heroItems->count())
                ->get();
            $heroItems = $heroItems->concat($fillArticles)->values();
        }

        $usedArticleIds = $heroItems->whereInstanceOf(Article::class)->pluck('id')->toArray();
        $usedExternalIds = $heroItems->whereInstanceOf(ExternalHeadline::class)->pluck('id')->toArray();

        // 2. Breaking News (Curated top 4 urgent/latest stories, strictly not all news)
        $breakingNews = Article::with(['categories', 'author'])
            ->published()
            ->whereNotIn('id', $usedArticleIds)
            ->latest('published_at')
            ->take(4)
            ->get();

        $usedArticleIds = array_merge($usedArticleIds, $breakingNews->pluck('id')->toArray());

        // 3. Category 1, Category 2, Category 3 (Curated top 3 editorial categories)
        $categories = Category::whereIn('type', ['news', 'both'])
            ->whereNotIn('slug', ['makhapashhatha', 'vashashhasahatayahatha-lghakathae'])
            ->with(['articles' => function ($query) use ($usedArticleIds) {
                $query->published()
                    ->whereNotIn('articles.id', $usedArticleIds)
                    ->latest('published_at')
                    ->limit(4)
                    ->with(['categories', 'author']);
            }])
            ->get()
            ->filter(fn ($cat) => $cat->articles->count() >= 2)
            ->sortByDesc(fn ($cat) => $cat->articles->count())
            ->values();

        $category1 = $categories->get(0);
        $category2 = $categories->get(1);
        $category3 = $categories->get(2);

        // 4. Yellow Pages / Local Businesses Showcase
        $featuredBusinesses = Business::with(['categories', 'neighborhoods'])
            ->where('status', '!=', 'pending_review')
            ->latest()
            ->take(4)
            ->get();

        // Top ticker & neighborhoods
        $neighborhoods = Neighborhood::orderBy('name')->get();
        $externalHeadlines = ExternalHeadline::with('newsSource')->latest('published_at')->take(8)->get();

        // 5. Admin-managed advertisements
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
            'externalHeadlines',
            'advertisements'
        ));
    }
}
