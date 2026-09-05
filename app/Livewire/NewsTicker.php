<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\ExternalHeadline;
use Livewire\Component;

class NewsTicker extends Component
{
    public function render()
    {
        $articles = Article::published()->latest('published_at')->take(10)->get();
        $externalHeadlines = ExternalHeadline::latest('published_at')->take(10)->get();
        
        $headlines = $articles->concat($externalHeadlines)
            ->sortByDesc(function ($item) {
                return $item->published_at ?? $item->created_at;
            })
            ->take(10)
            ->values();
        
        return view('livewire.news-ticker', [
            'headlines' => $headlines
        ]);
    }
}
