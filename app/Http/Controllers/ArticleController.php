<?php

namespace App\Http\Controllers;

use App\Models\Article;

class ArticleController extends Controller
{
    public function show(Article $article)
    {
        $article->load(['categories', 'neighborhoods', 'author', 'mentionedBusinesses']);

        return view('articles.show', compact('article'));
    }
}
