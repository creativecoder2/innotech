<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminActionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admin_name',
        'admin_role',
        'module',
        'action',
        'method',
        'url',
        'ip_address',
        'location',
        'device_type',
        'browser',
        'os',
        'status',
        'status_code',
        'error_message',
        'request_data',
    ];

    protected $casts = [
        'status_code' => 'integer',
        'request_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for filtering by module
     */
    public function scopeModule($query, $module)
    {
        if (!empty($module)) {
            return $query->where('module', $module);
        }
        return $query;
    }

    /**
     * Scope for filtering by status (success / failed / error)
     */
    public function scopeStatus($query, $status)
    {
        if (!empty($status)) {
            return $query->where('status', $status);
        }
        return $query;
    }
}
