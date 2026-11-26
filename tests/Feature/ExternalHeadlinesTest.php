<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ExternalHeadline;
use App\Models\NewsSource;
use App\Services\HeadlineCategorizerService;
use App\Services\HeadlineSummarizer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExternalHeadlinesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_summarizer_falls_back_to_title_when_provider_is_fallback(): void
    {
        config(['summarizer.provider' => 'fallback']);

        $summarizer = new HeadlineSummarizer;

        $title = 'Test Headline';
        $summary = $summarizer->summarize($title);

        $this->assertEquals($title, $summary);
    }

    public function test_auto_categorizes_mahakal_headline_into_dharma_culture(): void
    {
        $source = NewsSource::factory()->create();
        $headline = ExternalHeadline::create([
            'news_source_id' => $source->id,
            'title' => 'उज्जैन महाकाल मंदिर में भस्म आरती के दौरान उमड़ा श्रद्धालुओं का जनसैलाब',
            'source_url' => 'https://example.com/mahakal-news',
            'guid' => 'test-guid-1',
            'published_at' => now(),
            'fetched_at' => now(),
        ]);

        $service = app(HeadlineCategorizerService::class);
        $service->categorize($headline);

        $this->assertTrue(
            $headline->fresh()->categories->pluck('name')->contains('धर्म एवं संस्कृति')
        );
    }

    public function test_auto_categorizes_crime_headline(): void
    {
        $source = NewsSource::factory()->create();
        $headline = ExternalHeadline::create([
            'news_source_id' => $source->id,
            'title' => 'उज्जैन पुलिस ने चोरी के मामले में दो आरोपियों को किया गिरफ्तार',
            'source_url' => 'https://example.com/crime-news',
            'guid' => 'test-guid-2',
            'published_at' => now(),
            'fetched_at' => now(),
        ]);

        $service = app(HeadlineCategorizerService::class);
        $service->categorize($headline);

        $this->assertTrue(
            $headline->fresh()->categories->pluck('name')->contains('अपराध एवं सुरक्षा')
        );
    }

    public function test_categorize_headlines_artisan_command(): void
    {
        $source = NewsSource::factory()->create();
        ExternalHeadline::create([
            'news_source_id' => $source->id,
            'title' => 'उज्जैन में आज का मौसम: हल्की बारिश और बादलों का डेरा',
            'source_url' => 'https://example.com/weather-news',
            'guid' => 'test-guid-3',
            'published_at' => now(),
            'fetched_at' => now(),
        ]);

        $this->artisan('app:categorize-headlines')
            ->expectsOutputToContain('Starting automated headline categorization')
            ->expectsOutputToContain('Successfully categorized')
            ->assertExitCode(0);
    }

    public function test_auto_categorizes_into_ujjain_news_category(): void
    {
        $source = NewsSource::factory()->create();
        $headline = ExternalHeadline::create([
            'news_source_id' => $source->id,
            'title' => 'उज्जैन के फ्रीगंज में नया ट्रैफिक प्लान लागू, वाहन चालकों को राहत',
            'source_url' => 'https://example.com/ujjain-traffic',
            'guid' => 'test-guid-traffic-1',
            'published_at' => now(),
            'fetched_at' => now(),
        ]);

        $service = app(HeadlineCategorizerService::class);
        $service->categorize($headline);

        $freshCats = $headline->fresh()->categories->pluck('slug');
        $this->assertTrue($freshCats->contains('ujjain-news'));
    }

    public function test_external_news_index_can_be_filtered_by_category(): void
    {
        $source = NewsSource::factory()->create();
        $ujjainCat = Category::firstOrCreate(['slug' => 'ujjain-news'], ['name' => 'उज्जैन समाचार', 'type' => 'both']);
        $otherCat = Category::firstOrCreate(['slug' => 'sports-news'], ['name' => 'खेल', 'type' => 'both']);

        $ujjainHeadline = ExternalHeadline::create([
            'news_source_id' => $source->id,
            'title' => 'उज्जैन स्मार्ट सिटी प्रोजेक्ट का नया चरण शुरू',
            'source_url' => 'https://example.com/ujjain-smart-city',
            'guid' => 'test-guid-sc-1',
            'published_at' => now(),
            'fetched_at' => now(),
        ]);
        $ujjainHeadline->categories()->attach($ujjainCat->id);

        $otherHeadline = ExternalHeadline::create([
            'news_source_id' => $source->id,
            'title' => 'क्रिकेट मैच में भारत की शानदार जीत',
            'source_url' => 'https://example.com/cricket-win',
            'guid' => 'test-guid-cricket-1',
            'published_at' => now(),
            'fetched_at' => now(),
        ]);
        $otherHeadline->categories()->attach($otherCat->id);

        $response = $this->get(route('external.index', ['category' => 'ujjain-news']));
        $response->assertStatus(200);
        $response->assertSee('उज्जैन स्मार्ट सिटी प्रोजेक्ट');
        $response->assertDontSee('क्रिकेट मैच में भारत की शानदार जीत');
    }
}
