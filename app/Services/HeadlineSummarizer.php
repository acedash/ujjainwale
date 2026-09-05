<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HeadlineSummarizer
{
    /**
     * Generate a short 1-sentence summary of the headline.
     * Fallback to the original title if AI is not configured or fails.
     */
    public function summarize(string $title, ?string $snippet = null): string
    {
        $provider = config('summarizer.provider', 'fallback');

        if ($provider === 'openai') {
            return $this->summarizeWithOpenAI($title, $snippet);
        }

        // Default / Fallback: Just return the title
        return $title;
    }

    protected function summarizeWithOpenAI(string $title, ?string $snippet = null): string
    {
        $apiKey = config('summarizer.openai.api_key');
        
        if (empty($apiKey)) {
            Log::warning('HeadlineSummarizer: OpenAI provider selected but no API key configured. Falling back to title.');
            return $title;
        }

        $prompt = "You are a professional local news editor. I will provide a headline and potentially a short snippet from another local news outlet. Your job is to write a single, original sentence that briefly explains what the story is about, without copying their exact phrasing. Do not editorialize. Just state the facts of the story in one short sentence.\n\nHeadline: {$title}\n";
        
        if ($snippet) {
            $prompt .= "Snippet: {$snippet}\n";
        }

        try {
            $response = Http::withToken($apiKey)
                ->timeout(10)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => config('summarizer.openai.model', 'gpt-4o-mini'),
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'max_tokens' => 50,
                    'temperature' => 0.3,
                ]);

            if ($response->successful()) {
                $summary = $response->json('choices.0.message.content');
                if ($summary) {
                    return trim($summary);
                }
            } else {
                Log::error('HeadlineSummarizer OpenAI API error: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('HeadlineSummarizer Exception: ' . $e->getMessage());
        }

        // Fallback to title on any error
        return $title;
    }
}
