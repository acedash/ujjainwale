<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\ExternalHeadline;
use App\Models\NewsSource;
use App\Services\HeadlineCategorizerService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SimplePie\SimplePie;

class FetchExternalHeadlines extends Command
{
    protected $signature = 'app:fetch-headlines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch external headlines from active RSS sources';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sources = NewsSource::where('active', true)->get();

        $this->info("Found {$sources->count()} active sources.");
        $newCount = 0;

        foreach ($sources as $source) {
            $this->info("Fetching: {$source->name}");

            try {
                $feed = new SimplePie;
                $feed->set_feed_url($source->feed_url);
                $feed->enable_cache(false);
                $feed->init();

                $items = $feed->get_items();

                foreach ($items as $item) {
                    $guid = $item->get_id();
                    if (empty($guid)) {
                        $guid = hash('sha256', $item->get_permalink() ?? $item->get_title());
                    } elseif (strlen($guid) > 250) {
                        $guid = hash('sha256', $guid);
                    }

                    $headline = ExternalHeadline::where('guid', $guid)->first();

                    if (! $headline) {
                        $title = $item->get_title();
                        if (empty($title)) {
                            continue;
                        } // skip items without titles

                        $snippet = strip_tags($item->get_description() ?? '');

                        $thumbnail = null;
                        if ($enclosure = $item->get_enclosure()) {
                            $thumbnail = $enclosure->get_link();
                        }

                        $headline = ExternalHeadline::create([
                            'news_source_id' => $source->id,
                            'title' => $title,
                            'original_snippet' => $snippet,
                            'ai_summary' => null,
                            'source_url' => $item->get_permalink(),
                            'thumbnail_url' => $thumbnail,
                            'guid' => $guid,
                            'published_at' => $item->get_date('Y-m-d H:i:s'),
                            'fetched_at' => now(),
                        ]);
                        $newCount++;
                    }

                    if ($source->default_category_id) {
                        $headline->categories()->syncWithoutDetaching([$source->default_category_id]);
                    }

                    if ($categories = $item->get_categories()) {
                        foreach ($categories as $rssCategory) {
                            $term = trim($rssCategory->get_label() ?? $rssCategory->get_term());
                            if (! empty($term)) {
                                $category = Category::firstOrCreate(
                                    ['slug' => Str::slug($term)],
                                    [
                                        'name' => $term,
                                        'type' => 'news',
                                    ]
                                );
                                $headline->categories()->syncWithoutDetaching([$category->id]);
                            }
                        }
                    }

                    // Auto-categorize based on title & description content
                    app(HeadlineCategorizerService::class)->categorize($headline);
                }
            } catch (\Exception $e) {
                $this->error("Error fetching {$source->name}: ".$e->getMessage());
                Log::error("FetchExternalHeadlines error for {$source->name}: ".$e->getMessage());
            }
        }

        $this->info("Fetch complete. Added {$newCount} new headlines.");
    }
}
