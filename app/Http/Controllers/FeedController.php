<?php

namespace App\Http\Controllers;

use App\Models\Article;

class FeedController extends Controller
{
    public function rss()
    {
        $articles = Article::with('author')->published()
            ->latest('published_at')
            ->limit(20)
            ->get();

        return response()->view('feed.rss', compact('articles'))
            ->header('Content-Type', 'text/xml');
    }
}
