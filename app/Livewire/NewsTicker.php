<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\ExternalHeadline;
use Livewire\Component;

class NewsTicker extends Component
{
    public function render()
    {
        // Get top 10 latest news items (original content only for cleaner ticker)
        $headlines = Article::published()->latest('published_at')->take(10)->get();
        
        return view('livewire.news-ticker', [
            'headlines' => $headlines
        ]);
    }
}
