<?php

namespace Tests\Feature;

use App\Livewire\GlobalSearch;
use App\Models\Article;
use App\Models\Business;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class GlobalSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_global_search_renders_successfully()
    {
        Livewire::test(GlobalSearch::class)
            ->assertStatus(200);
    }

    public function test_global_search_finds_articles_and_businesses()
    {
        $article = Article::factory()->create([
            'title' => 'Mahakal Temple Updates',
            'status' => 'published',
            'published_at' => now()->subHour(),
        ]);

        $business = Business::factory()->create([
            'name' => 'Mahakal Sweets',
        ]);

        Livewire::test(GlobalSearch::class)
            ->set('search', 'Mahakal')
            ->assertSee('Mahakal Temple Updates')
            ->assertSee('Mahakal Sweets');
    }
}
