<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workspace extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'logo',
        'description',
        'email',
        'phone',
        'website',
        'country',
        'timezone',
        'status',
        'created_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'workspace_members'
        )->withPivot([
            'id',
            'role',
            'permissions',
            'status',
            'joined_at',
        ])->withTimestamps();
    }

    public function workspaceMembers(): HasMany
    {
        return $this->hasMany(WorkspaceMember::class);
    }

    public function businessCards(): HasMany
    {
        return $this->hasMany(BusinessCard::class);
    }

    public function themes(): HasMany
    {
        return $this->hasMany(CardTheme::class);
    }

    public function analytics(): HasMany
    {
        return $this->hasMany(CardAnalytic::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes and Helpers
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function isBusiness(): bool
    {
        return $this->type === 'business';
    }

    public function isPersonal(): bool
    {
        return $this->type === 'personal';
    }

    public function activeMembers()
    {
        return $this->members()
            ->wherePivot('status', 'active');
    }
}
