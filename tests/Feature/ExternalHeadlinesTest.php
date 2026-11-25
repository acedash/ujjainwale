<?php

namespace Tests\Feature;

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
}
