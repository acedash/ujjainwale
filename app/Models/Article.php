<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;
use App\Jobs\PostArticleToFacebook;

class Article extends Model implements HasMedia
{
    use HasFactory, HasSlug, InteractsWithMedia, HasTags;

    protected $fillable = ['author_id', 'title', 'slug', 'body', 'published_at', 'status', 'dek', 'meta_title', 'meta_description', 'is_featured'];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->where('published_at', '<=', now());
    }

    protected static function booted()
    {
        static::saving(function ($article) {
            if ($article->status === 'published' && is_null($article->published_at)) {
                $article->published_at = now();
            }
        });

        static::saved(function ($article) {
            // Check if it was just published and is set to post to facebook
            if (
                $article->status === 'published' &&
                $article->post_to_facebook &&
                !$article->posted_to_facebook
            ) {
                // If the status just changed to published in this request, dispatch the job
                if ($article->wasChanged('status') || $article->wasRecentlyCreated) {
                    PostArticleToFacebook::dispatch($article);
                }
            }
        });
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function neighborhoods(): BelongsToMany
    {
        return $this->belongsToMany(Neighborhood::class);
    }

    public function mentionedBusinesses(): MorphToMany
    {
        return $this->morphedByMany(Business::class, 'mentionable', 'mentions');
    }
}
