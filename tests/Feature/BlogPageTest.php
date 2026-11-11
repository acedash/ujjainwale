<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_page_returns_successful_response(): void
    {
        $response = $this->get('/blog');

        $response->assertStatus(200);
        $response->assertSee('Blog');
    }

    public function test_blog_page_displays_published_articles(): void
    {
        $published = Article::factory()->create([
            'title' => 'Ujjain Cultural Heritage Guide',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get('/blog');

        $response->assertStatus(200);
        $response->assertSee('Ujjain Cultural Heritage Guide');
    }

    public function test_blog_page_hides_draft_and_scheduled_articles(): void
    {
        $draft = Article::factory()->create([
            'title' => 'Secret Draft Article',
            'status' => 'draft',
        ]);

        $scheduled = Article::factory()->create([
            'title' => 'Future Scheduled Article',
            'status' => 'published',
            'published_at' => now()->addDays(5),
        ]);

        $response = $this->get('/blog');

        $response->assertStatus(200);
        $response->assertDontSee('Secret Draft Article');
        $response->assertDontSee('Future Scheduled Article');
    }

    public function test_blog_page_can_filter_by_category(): void
    {
        $categoryA = Category::factory()->create(['name' => 'History', 'slug' => 'history']);
        $categoryB = Category::factory()->create(['name' => 'Sports', 'slug' => 'sports']);

        $articleA = Article::factory()->create([
            'title' => 'Ancient Temples of Ujjain',
            'status' => 'published',
            'published_at' => now()->subHour(),
        ]);
        $articleA->categories()->attach($categoryA);

        $articleB = Article::factory()->create([
            'title' => 'Local Cricket Championship',
            'status' => 'published',
            'published_at' => now()->subHour(),
        ]);
        $articleB->categories()->attach($categoryB);

        $response = $this->get('/blog?category=history');

        $response->assertStatus(200);
        $response->assertSee('Ancient Temples of Ujjain');
        $response->assertDontSee('Local Cricket Championship');
    }

    public function test_blog_page_can_search_articles(): void
    {
        Article::factory()->create([
            'title' => 'Special Sweets in Freeganj',
            'status' => 'published',
            'published_at' => now()->subHour(),
        ]);

        Article::factory()->create([
            'title' => 'Mahakal Corridor Architecture',
            'status' => 'published',
            'published_at' => now()->subHour(),
        ]);

        $response = $this->get('/blog?search=Freeganj');

        $response->assertStatus(200);
        $response->assertSee('Special Sweets in Freeganj');
        $response->assertDontSee('Mahakal Corridor Architecture');
    }

    public function test_masthead_includes_blog_navigation_link(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee(route('blog'));
    }
}
