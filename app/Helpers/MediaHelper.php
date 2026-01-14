<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (! function_exists('media')) {

    /**
     * Get public URL for any stored media (local public / storage / s3)
     *
     * @param  string|null  $path
     * @param  bool  $signed
     * @return string|null
     */
    function media(?string $path, bool $signed = false): ?string
    {
        if (! $path) {
            return null;
        }

        // Clean the path
        $path = ltrim($path, '/');

        // 1. Check if the path is already a complete URL
        // (Agar path pehle se hi http/https hai to wahi return karega)
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        // 2. Check if file exists in the 'public' folder directly
        // (Ye check karega ki kya file public directory me physically majood hai like 'images/logo.png')
        if (file_exists(public_path($path))) {
            return asset($path);
        }

        // 3. Fallback to Storage (S3 or Local Storage)
        $disk = config('filesystems.default', 'local');

        // Signed URL support (Only for S3/compatible drivers)
        if ($signed && method_exists(Storage::disk($disk), 'temporaryUrl')) {

            return Storage::temporaryUrl(
                $path,
                now()->addMinutes(10)
            );
        }

        return Storage::url($path);
    }
}
