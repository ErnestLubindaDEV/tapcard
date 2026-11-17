<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class StorageUrl
{
    public static function url(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        $path = ltrim($path, '/');

        if (!Storage::disk('public')->exists($path)) {
            return null;
        }

        // Prefer Laravel's standard storage URL when available.
        $storageUrl = Storage::disk('public')->url($path);

        $storagePath = public_path('storage/' . $path);

        if (file_exists($storagePath)) {
            return $storageUrl;
        }

        // Fall back to the Laravel media route.
        return route('media.show', ['path' => $path]);
    }
}