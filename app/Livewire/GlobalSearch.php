<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\Business;
use Livewire\Component;

class GlobalSearch extends Component
{
    public $search = '';

    public function render()
    {
        $articles = [];
        $businesses = [];

        if (strlen($this->search) >= 2) {
            $articles = Article::published()
                ->with('media')
                ->where(function ($query) {
                    $query->where('title', 'like', '%'.$this->search.'%')
                          ->orWhere('body', 'like', '%'.$this->search.'%');
                })
                ->latest('published_at')
                ->limit(5)
                ->get();

            $businesses = Business::with('media')
                ->where('name', 'like', '%'.$this->search.'%')
                ->limit(5)
                ->get();
        }

        return view('livewire.global-search', [
            'articles' => $articles,
            'businesses' => $businesses,
        ]);
    }
}
