<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'description',
        'client',
        'date',
        'image',
        'images',
        'link',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'images' => 'array',
    ];

    /**
     * Get all images including cover and inner images.
     */
    public function getAllImagesAttribute(): array
    {
        $list = [];
        if (!empty($this->image)) {
            $list[] = $this->image;
        }

        if (!empty($this->images) && is_array($this->images)) {
            foreach ($this->images as $img) {
                if ($img && !in_array($img, $list)) {
                    $list[] = $img;
                }
            }
        }

        if (empty($list)) {
            $list[] = 'assets/img/gallery/gal-thum-01.jpg';
        }

        return array_values($list);
    }

    /**
     * Get only inner images excluding cover.
     */
    public function getInnerImagesAttribute(): array
    {
        if (!empty($this->images) && is_array($this->images)) {
            return array_values(array_filter($this->images));
        }
        return [];
    }

    /**
     * Total number of photos.
     */
    public function getPhotosCountAttribute(): int
    {
        return count($this->all_images);
    }
}
