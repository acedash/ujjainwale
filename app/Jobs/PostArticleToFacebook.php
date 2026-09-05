<?php

namespace App\Jobs;

use App\Models\Article;
use App\Services\FacebookService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PostArticleToFacebook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Article $article;

    /**
     * Create a new job instance.
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * Execute the job.
     */
    public function handle(FacebookService $facebookService): void
    {
        // Double check it wasn't already posted
        if ($this->article->posted_to_facebook) {
            return;
        }

        $success = $facebookService->postArticle($this->article);

        if ($success) {
            $this->article->posted_to_facebook = true;
            $this->article->saveQuietly();
        }
    }
}
