<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CardTheme extends Model
{
    protected $fillable = [
        'workspace_id',
        'name',
        'slug',
        'description',
        'preview_image',
        'colors',
        'settings',
        'is_active',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'colors' => 'array',
            'settings' => 'array',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ];
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function businessCards(): HasMany
    {
        return $this->hasMany(BusinessCard::class);
    }
}
