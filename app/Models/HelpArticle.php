<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HelpArticle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'help_category_id',  // ← PERBAIKAN: pastikan ini ada
        'title',
        'slug',
        'excerpt',
        'content',
        'icon',
        'image',
        'read_time',
        'is_featured',
        'is_active',
        'views',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'views' => 'integer',
        'read_time' => 'integer',
    ];

    /**
     * RELASI: Artikel milik 1 kategori
     * PERBAIKAN: foreign key = help_category_id
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(HelpCategory::class, 'help_category_id');  // ← PERBAIKAN
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }

    public function getFormattedReadTimeAttribute(): string
    {
        return $this->read_time . ' menit';
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('is_active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $categorySlug)
    {
        return $query->whereHas('category', function ($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }
}
