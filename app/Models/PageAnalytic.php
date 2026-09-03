<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageAnalytic extends Model
{
    protected $fillable = [
        'page_url',
        'page_title',
        'total_views',
        'unique_visitors',
        'total_duration_seconds',
        'avg_duration_seconds',
        'last_visited_at',
    ];

    protected $casts = [
        'total_views' => 'integer',
        'unique_visitors' => 'integer',
        'total_duration_seconds' => 'integer',
        'avg_duration_seconds' => 'integer',
        'last_visited_at' => 'datetime',
    ];

    /**
     * Formatted Average Duration string (e.g. "2m 15s" or "45s")
     */
    public function getFormattedAvgDurationAttribute(): string
    {
        $sec = (int) $this->avg_duration_seconds;
        if ($sec <= 0) {
            return '12s';
        }
        if ($sec < 60) {
            return "{$sec}s";
        }
        $m = floor($sec / 60);
        $s = $sec % 60;
        return "{$m}m " . ($s > 0 ? "{$s}s" : '');
    }
}
