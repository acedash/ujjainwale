<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use App\Services\ArticleCategorizerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleCategorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_categorizes_mahakal_article_into_dharma_culture(): void
    {
        $user = User::factory()->create();
        $article = Article::create([
            'author_id' => $user->id,
            'title' => 'उज्जैन महाकाल मंदिर में भस्म आरती के दौरान उमड़ा श्रद्धालुओं का सैलाब',
            'slug' => 'mahakal-bhasma-aarti-darshan',
            'body' => '<p>महाकालेश्वर ज्योतिर्लिंग मंदिर में सावन मास के दौरान विशेष पूजा अर्चना और भस्म आरती संपन्न हुई।</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $service = app(ArticleCategorizerService::class);
        $service->categorize($article);

        $this->assertTrue(
            $article->fresh()->categories->pluck('name')->contains('धर्म एवं संस्कृति')
        );
    }

    public function test_categorizes_food_article_into_food_cuisine(): void
    {
        $user = User::factory()->create();
        $article = Article::create([
            'author_id' => $user->id,
            'title' => 'उज्जैन की कचौरीयां और पोहा जलेबी का लाजवाब स्वाद',
            'slug' => 'ujjain-kachori-poha-jalebi',
            'body' => '<p>नाश्ते में अगर गरम गरम कचौरी और पोहा के साथ जलेबी मिल जाए तो मालवा का जायका पूरा हो जाता है।</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $service = app(ArticleCategorizerService::class);
        $service->categorize($article);

        $this->assertTrue(
            $article->fresh()->categories->pluck('name')->contains('खान-पान एवं स्वाद')
        );
    }

    public function test_categorizes_history_article_into_history_heritage(): void
    {
        $user = User::factory()->create();
        $article = Article::create([
            'author_id' => $user->id,
            'title' => 'सम्राट विक्रमादित्य और प्राचीन उज्जयिनी का गौरवशाली इतिहास',
            'slug' => 'samrat-vikramaditya-ancient-history',
            'body' => '<p>अवन्तिका नगरी के सम्राट विक्रमादित्य के नवरत्न और वेधशाला का ऐतिहासिक महत्व आज भी अद्वितीय है।</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $service = app(ArticleCategorizerService::class);
        $service->categorize($article);

        $this->assertTrue(
            $article->fresh()->categories->pluck('name')->contains('इतिहास एवं विरासत')
        );
    }

    public function test_detaches_mukhpreshth_when_categorizing(): void
    {
        $user = User::factory()->create();
        $mukhpreshth = Category::create([
            'name' => 'मुखपृष्ठ',
            'slug' => 'makhapashhatha',
            'type' => 'both',
        ]);

        $article = Article::create([
            'author_id' => $user->id,
            'title' => 'अगस्त्य संहिता का विद्युत्-शास्त्र',
            'slug' => 'agastya-samhita-vidyut-shastra',
            'body' => '<p>विद्युत बैटरी का प्राचीन भारतीय विज्ञान और शोध का विवरण।</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $article->categories()->attach($mukhpreshth->id);
        $this->assertTrue($article->fresh()->categories->pluck('name')->contains('मुखपृष्ठ'));

        $service = app(ArticleCategorizerService::class);
        $service->categorize($article);

        $freshCategories = $article->fresh()->categories->pluck('name');
        $this->assertFalse($freshCategories->contains('मुखपृष्ठ'));
        $this->assertTrue($freshCategories->contains('शिक्षा एवं ज्ञान') || $freshCategories->contains('इतिहास एवं विरासत'));
    }

    public function test_artisan_command_categorizes_articles(): void
    {
        $user = User::factory()->create();
        Article::create([
            'author_id' => $user->id,
            'title' => 'उज्जैन सराफा बाजार में सोने और चांदी के भाव में उछाल',
            'slug' => 'ujjain-sarafa-gold-silver-market',
            'body' => '<p>व्यापारियों के अनुसार आज सराफा मंडी में ग्राहकी में तेजी देखने को मिली।</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->artisan('app:categorize-articles', ['--force' => true])
            ->assertSuccessful();

        $article = Article::where('slug', 'ujjain-sarafa-gold-silver-market')->first();
        $this->assertTrue(
            $article->categories->pluck('name')->contains('व्यापार एवं उद्योग')
        );
    }
}
