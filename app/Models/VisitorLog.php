<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    protected $fillable = [
        'ip_address',
        'session_id',
        'visit_date',
        'device_type',
        'browser',
        'platform',
        'page_url',
        'page_title',
        'duration_seconds',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'duration_seconds' => 'integer',
    ];

    public function scopeToday($query)
    {
        return $query->where('visit_date', now()->toDateString());
    }

    public function scopeYesterday($query)
    {
        return $query->where('visit_date', now()->subDay()->toDateString());
    }

    public function scopeLastDays($query, int $days = 14)
    {
        return $query->where('visit_date', '>=', now()->subDays($days)->toDateString());
    }
}
