<?php

namespace Database\Seeders;

use App\Models\CardTheme;
use Illuminate\Database\Seeder;

class CardThemeSeeder extends Seeder
{
    public function run(): void
    {
        $themes = [
            [
                'name' => 'Midnight',
                'slug' => 'midnight',
                'description' => 'A modern dark theme with electric cyan accents.',
                'colors' => [
                    'background' => '#111827',
                    'surface' => '#1F2937',
                    'primary' => '#08D5DB',
                    'secondary' => '#B5FF58',
                    'text' => '#FFFFFF',
                    'muted' => '#9CA3AF',
                ],
                'settings' => [
                    'font' => 'Outfit',
                    'style' => 'dark',
                    'border_radius' => '16px',
                    'layout' => 'modern',
                ],
                'is_default' => false,
            ],
            [
                'name' => 'Executive',
                'slug' => 'executive',
                'description' => 'A professional corporate theme with blue accents.',
                'colors' => [
                    'background' => '#F8FAFC',
                    'surface' => '#FFFFFF',
                    'primary' => '#2563EB',
                    'secondary' => '#0F172A',
                    'text' => '#172554',
                    'muted' => '#64748B',
                ],
                'settings' => [
                    'font' => 'DM Sans',
                    'style' => 'light',
                    'border_radius' => '12px',
                    'layout' => 'corporate',
                ],
                'is_default' => true,
            ],
            [
                'name' => 'Prestige',
                'slug' => 'prestige',
                'description' => 'An elegant luxury theme with champagne gold accents.',
                'colors' => [
                    'background' => '#172B25',
                    'surface' => '#203A32',
                    'primary' => '#C5A46D',
                    'secondary' => '#E5D3A5',
                    'text' => '#FFFFFF',
                    'muted' => '#B6C4BC',
                ],
                'settings' => [
                    'font' => 'Playfair Display',
                    'style' => 'dark',
                    'border_radius' => '14px',
                    'layout' => 'luxury',
                ],
                'is_default' => false,
            ],
            [
                'name' => 'Creative',
                'slug' => 'creative',
                'description' => 'A bold creative theme with vibrant orange accents.',
                'colors' => [
                    'background' => '#FFF7ED',
                    'surface' => '#FFFFFF',
                    'primary' => '#EA580C',
                    'secondary' => '#F97316',
                    'text' => '#431407',
                    'muted' => '#9A3412',
                ],
                'settings' => [
                    'font' => 'Outfit',
                    'style' => 'light',
                    'border_radius' => '20px',
                    'layout' => 'creative',
                ],
                'is_default' => false,
            ],
            [
                'name' => 'Minimal',
                'slug' => 'minimal',
                'description' => 'A clean contemporary theme with soft purple accents.',
                'colors' => [
                    'background' => '#EDE9FE',
                    'surface' => '#FFFFFF',
                    'primary' => '#7C3AED',
                    'secondary' => '#A78BFA',
                    'text' => '#312E81',
                    'muted' => '#6D28D9',
                ],
                'settings' => [
                    'font' => 'DM Sans',
                    'style' => 'light',
                    'border_radius' => '10px',
                    'layout' => 'minimal',
                ],
                'is_default' => false,
            ],
        ];

        foreach ($themes as $theme) {
            CardTheme::updateOrCreate(
                ['slug' => $theme['slug']],
                [
                    'workspace_id' => null,
                    'name' => $theme['name'],
                    'description' => $theme['description'],
                    'colors' => $theme['colors'],
                    'settings' => $theme['settings'],
                    'is_active' => true,
                    'is_default' => $theme['is_default'],
                ]
            );
        }
    }
}
