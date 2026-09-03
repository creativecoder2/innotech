<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'template_type',
        'content',
        'meta_description',
        'show_in_footer',
        'footer_placement',
        'order',
        'is_published',
    ];

    protected $casts = [
        'show_in_footer' => 'boolean',
        'is_published' => 'boolean',
        'order' => 'integer',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->orderBy('order', 'asc');
    }

    public function scopeFooterBottom($query)
    {
        return $query->where('is_published', true)
                     ->where('show_in_footer', true)
                     ->whereIn('footer_placement', ['bottom_bar', 'both'])
                     ->orderBy('order', 'asc');
    }

    public function scopeFooterUseful($query)
    {
        return $query->where('is_published', true)
                     ->where('show_in_footer', true)
                     ->whereIn('footer_placement', ['useful_links', 'both'])
                     ->orderBy('order', 'asc');
    }
}
