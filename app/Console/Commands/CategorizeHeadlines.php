<?php

namespace App\Console\Commands;

use App\Services\HeadlineCategorizerService;
use Illuminate\Console\Command;

class CategorizeHeadlines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:categorize-headlines {--force : Re-scan and categorize all headlines, even already categorized ones}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically categorize external headlines based on intelligent keyword matching and source defaults';

    /**
     * Execute the console command.
     */
    public function handle(HeadlineCategorizerService $service): int
    {
        $this->info('Starting automated headline categorization...');

        $force = (bool) $this->option('force');
        $categorizedCount = $service->categorizeAll(onlyUncategorized: ! $force);

        $this->info("Successfully categorized {$categorizedCount} headlines.");

        return Command::SUCCESS;
    }
}
