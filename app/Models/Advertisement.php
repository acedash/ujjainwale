<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Advertisement extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public const PLACEMENTS = [
        'billboard' => 'Billboard (Home Top)',
        'sponsor_card' => 'Local Sponsor Card',
        'in_feed' => 'In-Feed Banner (Between Categories)',
        'left_rail' => 'Left Gutter Skyscraper (Desktop)',
        'right_rail' => 'Right Gutter Skyscraper (Desktop)',
        'sidebar' => 'Sidebar Banner',
    ];

    protected $fillable = [
        'title',
        'placement',
        'subtitle',
        'badge_text',
        'description',
        'cta_text',
        'cta_url',
        'phone',
        'icon',
        'ad_code',
        'is_active',
        'order',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(function (Builder $q) {
                $q->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function (Builder $q) {
                $q->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            });
    }

    public function scopePlacement(Builder $query, string $placement): Builder
    {
        return $query->where('placement', $placement);
    }
}
