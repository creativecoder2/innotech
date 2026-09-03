<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'tags',
        'author',
        'image',
        'video_url',
        'slider_images',
        'summary',
        'content',
        'quote',
        'quote_author',
        'approach_title',
        'approach_text',
        'approach_points',
        'meta_image_1',
        'meta_image_2',
        'status',
        'views',
        'is_published',
        'published_at'
    ];

    protected $casts = [
        'published_at' => 'date',
        'views' => 'integer',
        'is_published' => 'boolean',
        'slider_images' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }
            if (empty($blog->published_at)) {
                $blog->published_at = now();
            }
            if (!isset($blog->is_published)) {
                $blog->is_published = ($blog->status === 'published');
            }
        });

        static::updating(function ($blog) {
            if (isset($blog->status)) {
                $blog->is_published = ($blog->status === 'published');
            }
        });
    }

    /**
     * Scope for published blogs.
     */
    public function scopePublished($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'published')
              ->orWhere('is_published', true);
        });
    }

    /**
     * Get tags as trimmed array.
     */
    public function getTagsArrayAttribute()
    {
        if (empty($this->tags)) {
            return [];
        }
        return array_values(array_filter(array_map('trim', explode(',', $this->tags))));
    }

    /**
     * Get approach bullet points as array.
     */
    public function getApproachPointsArrayAttribute()
    {
        if (empty($this->approach_points)) {
            return [];
        }
        $lines = preg_split('/\r\n|\r|\n/', $this->approach_points);
        return array_values(array_filter(array_map('trim', $lines)));
    }

    /**
     * Calculate reading time in minutes.
     */
    public function getReadingTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->content . ' ' . $this->summary));
        $minutes = max(1, (int) ceil($wordCount / 200));
        return $minutes . ' min read';
    }

    /**
     * All comments for this blog.
     */
    public function comments()
    {
        return $this->hasMany(BlogComment::class)->orderBy('created_at', 'desc');
    }

    /**
     * Only approved comments visible on website.
     */
    public function approvedComments()
    {
        return $this->hasMany(BlogComment::class)
            ->where(function ($q) {
                $q->where('status', 'approved')
                  ->orWhere('is_approved', true);
            })
            ->orderBy('created_at', 'desc');
    }
}
