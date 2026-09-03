<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'icon',
        'image',
        'banner_image',
        'banner_subtitle',
        'image_2',
        'process_title',
        'short_description',
        'description',
        'features',
        'steps_title',
        'steps_description',
        'step_1_title',
        'step_1_points',
        'step_2_title',
        'step_2_points',
        'step_3_title',
        'step_3_points',
        'step_4_title',
        'step_4_points',
        'research_title',
        'research_description',
        'research_image',
        'bottom_link_text',
        'bottom_link_url',
        'is_featured',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'order' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->title);
            }
        });
    }
}
