<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'designation',
        'expertise',
        'experience',
        'email',
        'phone',
        'bio',
        'personal_experience',
        'skills',
        'education',
        'awards',
        'image',
        'youtube_url',
        'twitter_url',
        'facebook_url',
        'instagram_url',
        'pinterest_url',
        'skype_url',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($member) {
            if (empty($member->slug)) {
                $member->slug = Str::slug($member->name);
            }
        });
    }

    /**
     * Get skills as array
     */
    public function getSkillsArrayAttribute()
    {
        if (empty($this->skills)) {
            return [];
        }
        return array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $this->skills)))));
    }

    /**
     * Get education as array
     */
    public function getEducationArrayAttribute()
    {
        if (empty($this->education)) {
            return [];
        }
        return array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $this->education)))));
    }

    /**
     * Get awards as array
     */
    public function getAwardsArrayAttribute()
    {
        if (empty($this->awards)) {
            return [];
        }
        return array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $this->awards)))));
    }
}
