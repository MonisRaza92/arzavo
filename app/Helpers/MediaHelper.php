<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (!function_exists('media')) {

    /**
     * Get public URL for any stored media (local public / storage / s3)
     *
     * @param  string|null  $path
     * @param  bool  $signed
     * @return string|null
     */
    function media(?string $path, bool $signed = false): ?string
    {
        if (!$path) {
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

if (!function_exists('image')) {

    function image(?string $path = null): ?string
    {
        // Agar path diya hai to media helper use karo
        if ($path) {
            return media($path);
        }

        $files = cache()->remember('tenant_demo_images', 3600, function () {
            return glob(public_path('images/tenant/*.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}'), GLOB_BRACE);
        });

        if (!$files) {
            return null;
        }

        $file = $files[array_rand($files)];

        return asset('images/tenant/' . basename($file));
    }
}

if (!function_exists('video')) {

    function video(?string $path = null): ?string
    {
        if ($path) {
            return media($path);
        }

        $files = cache()->remember('tenant_demo_videos', 3600, function () {
            return glob(public_path('videos/tenant/*.{mp4,webm,ogg,MP4,WEBM,OGG}'), GLOB_BRACE);
        });

        if (!$files) {
            return null;
        }

        $file = $files[array_rand($files)];

        return asset('videos/tenant/' . basename($file));
    }
}