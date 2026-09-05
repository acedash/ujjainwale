<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExternalHeadlinesTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_summarizer_falls_back_to_title_when_provider_is_fallback(): void
    {
        config(['summarizer.provider' => 'fallback']);

        $summarizer = new \App\Services\HeadlineSummarizer();
        
        $title = 'Test Headline';
        $summary = $summarizer->summarize($title);

        $this->assertEquals($title, $summary);
    }
}
