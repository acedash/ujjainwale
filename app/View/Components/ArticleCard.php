<?php

namespace App\View\Components;

use App\Models\Article;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ArticleCard extends Component
{
    public $article;

    public $variant;

    public function __construct($article, string $variant = 'standard')
    {
        $this->article = $article;
        $this->variant = $variant;
    }

    public function render(): View|Closure|string
    {
        return view('components.article-card');
    }
}
