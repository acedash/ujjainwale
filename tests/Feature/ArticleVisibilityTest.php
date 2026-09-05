<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_hides_draft_articles_from_the_homepage()
    {
        $draft = Article::factory()->create(['status' => 'draft']);
        
        $response = $this->get('/');
        $response->assertDontSee($draft->title);
    }

    public function test_hides_future_scheduled_articles_from_the_homepage()
    {
        $scheduled = Article::factory()->create([
            'status' => 'published',
            'published_at' => now()->addDays(2),
        ]);
        
        $response = $this->get('/');
        $response->assertDontSee($scheduled->title);
    }

    public function test_shows_currently_published_articles_on_the_homepage()
    {
        $published = Article::factory()->create([
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);
        
        $response = $this->get('/');
        $response->assertSee($published->title);
    }

    public function test_hides_draft_articles_from_category_pages()
    {
        $category = Category::factory()->create();
        $draft = Article::factory()->create(['status' => 'draft']);
        $draft->categories()->attach($category);
        
        $response = $this->get("/category/{$category->slug}");
        $response->assertDontSee($draft->title);
    }
}
