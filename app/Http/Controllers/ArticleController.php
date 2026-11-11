<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::published()
            ->with(['media', 'author', 'categories'])
            ->latest('published_at');

        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->query('category'));
            });
        }

        if ($request->filled('search')) {
            $searchTerm = $request->query('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%'.$searchTerm.'%')
                    ->orWhere('body', 'like', '%'.$searchTerm.'%');
            });
        }

        $articles = $query->paginate(12)->withQueryString();
        $categories = Category::has('articles')->orderBy('name')->get();

        return view('articles.index', compact('articles', 'categories'));
    }

    public function show(Article $article)
    {
        $article->load(['categories', 'neighborhoods', 'author', 'mentionedBusinesses']);

        return view('articles.show', compact('article'));
    }
}
