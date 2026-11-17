<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Workspace Relationships
    |--------------------------------------------------------------------------
    */

    public function workspaces(): BelongsToMany
    {
        return $this->belongsToMany(
            Workspace::class,
            'workspace_members'
        )->withPivot([
            'id',
            'role',
            'permissions',
            'status',
            'joined_at',
        ])->withTimestamps();
    }

    public function workspaceMemberships(): HasMany
    {
        return $this->hasMany(WorkspaceMember::class);
    }

    public function businessCards(): HasMany
    {
        return $this->hasMany(BusinessCard::class);
    }

    public function createdBusinessCards(): HasMany
    {
        return $this->hasMany(
            BusinessCard::class,
            'created_by'
        );
    }

    public function createdWorkspaces(): HasMany
    {
        return $this->hasMany(
            Workspace::class,
            'created_by'
        );
    }

    public function activeWorkspaces(): BelongsToMany
    {
        return $this->workspaces()
            ->wherePivot('status', 'active');
    }
}
