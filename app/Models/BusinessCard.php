<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class BusinessCard extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'workspace_id',
        'created_by',
        'user_id',
        'card_theme_id',
        'slug',
        'first_name',
        'last_name',
        'job_title',
        'company_name',
        'bio',
        'profile_photo',
        'cover_photo',
        'email',
        'phone',
        'whatsapp',
        'website',
        'address',
        'city',
        'country',
        'status',
        'visibility',
        'is_primary',
        'show_email',
        'show_phone',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'show_email' => 'boolean',
            'show_phone' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(CardTheme::class, 'card_theme_id');
    }

    public function socialLinks(): HasMany
    {
        return $this->hasMany(CardSocialLink::class)->orderBy('sort_order');
    }

    public function visibleSocialLinks(): HasMany
    {
        return $this->hasMany(CardSocialLink::class)
            ->where('is_visible', true)
            ->orderBy('sort_order');
    }

    public function analytics(): HasMany
    {
        return $this->hasMany(CardAnalytics::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getPublicUrlAttribute(): string
    {
        return route('cards.public', [
            'slug' => $this->slug,
        ]);
    }

    /**
     * Generate a unique card slug.
     */
    public static function generateUniqueSlug(string $name): string
    {
        $baseSlug = Str::slug($name);

        if ($baseSlug === '') {
            $baseSlug = 'card';
        }

        $slug = $baseSlug;
        $counter = 1;

        while (static::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
