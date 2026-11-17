<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WorkspaceMemberController extends Controller
{
    private function permissionsForRole(string $role): array
    {
        return match ($role) {
            'owner' => [
                'manage_workspace',
                'manage_members',
                'manage_cards',
                'manage_themes',
                'view_analytics',
            ],

            'admin' => [
                'manage_cards',
                'manage_themes',
                'view_analytics',
            ],

            'member' => [
                'manage_cards',
            ],

            default => [],
        };
    }
}
