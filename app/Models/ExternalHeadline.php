<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExternalHeadline extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_source_id',
        'title',
        'original_snippet',
        'ai_summary',
        'source_url',
        'thumbnail_url',
        'guid',
        'published_at',
        'fetched_at',
        'is_featured',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'fetched_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    public function newsSource(): BelongsTo
    {
        return $this->belongsTo(NewsSource::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
