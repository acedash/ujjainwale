<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImportWordPressPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:wp-posts {file? : The path to the CSV file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import WordPress posts from a CSV file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file') ?? base_path('posts-wp.csv');

        if (! File::exists($filePath)) {
            $this->error("File not found at: {$filePath}");

            return 1;
        }

        $defaultUser = User::first();
        if (! $defaultUser) {
            $this->error('No users found in the database. Please create a user first to assign as the author.');

            return 1;
        }

        $file = fopen($filePath, 'r');
        $header = fgetcsv($file);

        // Ensure UTF-8 BOM is removed from the first header
        $header[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header[0]);

        $count = 0;
        $this->info('Starting import...');

        while ($row = fgetcsv($file)) {
            $data = array_combine($header, $row);

            if (($data['Post Type'] ?? '') !== 'post') {
                continue;
            }

            $title = $data['Title'] ?? '';
            if (empty($title)) {
                continue;
            }

            // Map Status
            $status = 'draft';
            if (($data['Status'] ?? '') === 'publish') {
                $status = 'published';
            }

            // Generate slug if empty
            $slug = $data['Slug'] ?? '';
            if (empty($slug)) {
                $slug = Str::slug($title);
            }

            // Create Article
            $article = Article::firstOrNew(['slug' => $slug]);
            $article->author_id = $defaultUser->id;
            $article->title = $title;
            $article->body = $data['Content'] ?? '';
            $article->dek = $data['Excerpt'] ?? null;
            $article->published_at = ! empty($data['Date']) ? $data['Date'] : null;
            $article->status = $status;

            // Prevent duplicate facebook posting during import
            $article->post_to_facebook = false;
            $article->posted_to_facebook = true;

            $article->save();

            // Handle Categories
            if (! empty($data['Categories'])) {
                $categoryNames = array_filter(explode('|', $data['Categories']));
                $categoryIds = [];
                foreach ($categoryNames as $catName) {
                    $catName = trim($catName);
                    if ($catName) {
                        $category = Category::firstOrCreate(['slug' => Str::slug($catName)], ['name' => $catName]);
                        $categoryIds[] = $category->id;
                    }
                }
                $article->categories()->sync($categoryIds);
            }

            // Handle Tags
            if (! empty($data['Tags'])) {
                $tags = array_filter(array_map('trim', explode('|', $data['Tags'])));
                if (! empty($tags)) {
                    $article->attachTags($tags);
                }
            }

            // Handle Media
            if (! empty($data['Image URL']) && $article->getMedia('featured_image')->count() === 0 && $article->getMedia('cover')->count() === 0) {
                // Image URL can contain multiple pipe-separated URLs
                $imageUrls = explode('|', $data['Image URL']);
                $firstUrl = trim($imageUrls[0]);

                if (! empty($firstUrl)) {
                    try {
                        $this->line("Downloading image for: {$title}");
                        $article->addMediaFromUrl($firstUrl)->toMediaCollection('featured_image');
                    } catch (\Exception $e) {
                        $this->warn("Failed to download image for '{$title}': ".$e->getMessage());
                    }
                }
            }

            $count++;
            if ($count % 10 === 0) {
                $this->info("Imported {$count} posts...");
            }
        }

        fclose($file);

        $this->info("Import completed successfully! Total imported: {$count}");

        return 0;
    }
}
