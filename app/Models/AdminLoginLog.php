<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminLoginLog extends Model
{
    protected $table = 'admin_login_logs';

    protected $fillable = [
        'user_id',
        'session_id',
        'email_or_phone',
        'ip_address',
        'location',
        'device_type',
        'os',
        'browser',
        'user_agent',
        'login_method',
        'status',
        'is_active_session',
        'last_activity_at',
        'logged_out_at',
    ];

    protected $casts = [
        'is_active_session' => 'boolean',
        'last_activity_at' => 'datetime',
        'logged_out_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Check if this session is currently active/online (active in last 5 minutes)
     */
    public function getIsOnlineAttribute(): bool
    {
        if (!$this->is_active_session) return false;
        if (!$this->last_activity_at) return false;
        return $this->last_activity_at->gte(now()->subMinutes(5));
    }

    /**
     * Scope for active sessions
     */
    public function scopeActiveSessions($query)
    {
        return $query->where('is_active_session', true);
    }

    /**
     * Scope for users currently online
     */
    public function scopeOnline($query)
    {
        return $query->where('is_active_session', true)
                     ->where('last_activity_at', '>=', now()->subMinutes(5));
    }
}
