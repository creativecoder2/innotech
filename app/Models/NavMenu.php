<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'url',
        'page_route',
        'parent_id',
        'order',
        'show_on_home',
        'show_on_inner',
        'is_active',
        'target_blank',
    ];

    protected $casts = [
        'show_on_home' => 'boolean',
        'show_on_inner' => 'boolean',
        'is_active' => 'boolean',
        'target_blank' => 'boolean',
        'order' => 'integer',
    ];

    public function parent()
    {
        return $this->belongsTo(NavMenu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(NavMenu::class, 'parent_id')->orderBy('order', 'asc');
    }

    public function getComputedUrlAttribute()
    {
        if (!empty($this->url) && !in_array($this->url, ['#', ''])) {
            if (str_starts_with($this->url, 'http://') || str_starts_with($this->url, 'https://') || str_starts_with($this->url, '#')) {
                return $this->url;
            }
            return url($this->url);
        }
        return '#';
    }

    public static function getHomeMenus()
    {
        return static::whereNull('parent_id')
            ->where('is_active', true)
            ->where('show_on_home', true)
            ->orderBy('order', 'asc')
            ->with(['children' => function($q) {
                $q->where('is_active', true)->where('show_on_home', true)->orderBy('order', 'asc');
            }])
            ->get();
    }

    public static function getInnerMenus()
    {
        return static::whereNull('parent_id')
            ->where('is_active', true)
            ->where('show_on_inner', true)
            ->orderBy('order', 'asc')
            ->with(['children' => function($q) {
                $q->where('is_active', true)->where('show_on_inner', true)->orderBy('order', 'asc');
            }])
            ->get();
    }
}
