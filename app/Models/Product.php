<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'slug',
        'sku',
        'image',
        'gallery',
        'short_description',
        'description',
        'key_features',
        'order',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
        'gallery' => 'array',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Return array of key features for display in list/bullets
     */
    public function getFeaturesListAttribute(): array
    {
        if (empty($this->key_features)) {
            return [];
        }

        // Try json decode first
        $decoded = json_decode($this->key_features, true);
        if (is_array($decoded)) {
            return array_values(array_filter(array_map('trim', $decoded)));
        }

        // Fallback: split by newlines
        $lines = preg_split("/\r\n|\n|\r/", $this->key_features);
        $features = [];
        foreach ($lines as $line) {
            $line = trim($line, " \t\n\r\0\x0B-•*");
            if (!empty($line)) {
                $features[] = $line;
            }
        }
        return $features;
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->title);
            }
        });
    }
}
