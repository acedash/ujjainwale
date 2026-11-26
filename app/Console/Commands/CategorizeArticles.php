<?php

namespace App\Console\Commands;

use App\Services\ArticleCategorizerService;
use Illuminate\Console\Command;

class CategorizeArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:categorize-articles 
                            {--force : Re-scan and re-categorize all articles, even already categorized ones}
                            {--append : Sync without detaching existing categories instead of replacing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically categorize blog articles based on intelligent keyword matching and cultural taxonomy';

    /**
     * Execute the console command.
     */
    public function handle(ArticleCategorizerService $service): int
    {
        $this->info('Starting automated article categorization...');

        $force = (bool) $this->option('force');
        $sync = ! (bool) $this->option('append');

        $result = $service->categorizeAll(force: $force, sync: $sync);

        $this->info("Successfully processed {$result['total_processed']} articles.");
        $this->info("Categorized {$result['categorized']} articles with {$result['categories_assigned']} category assignments.");

        return Command::SUCCESS;
    }
}
