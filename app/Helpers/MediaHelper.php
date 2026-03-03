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
        static $randomImages = null;

        /*
        |--------------------------------------------------------------------------
        | RANDOM IMAGE FALLBACK
        |--------------------------------------------------------------------------
        */
        if (!$path) {

            if ($randomImages === null) {

                $dir = public_path('images/tenant');

                $randomImages = collect(
                    glob($dir . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE)
                )
                    ->shuffle()
                    ->values();
            }

            $file = $randomImages->shift();

            return $file
                ? asset('images/tenant/' . basename($file))
                : null;
        }

        $path = ltrim($path, '/');

        // External URL
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        // Public file
        if (file_exists(public_path($path))) {
            return asset($path);
        }

        // Storage fallback
        return Storage::url($path);
    }
}
if (!function_exists('video')) {

    function video(?string $path = null): ?string
    {
        static $randomVideos = null;

        /*
        |--------------------------------------------------------------------------
        | RANDOM VIDEO FALLBACK
        |--------------------------------------------------------------------------
        */
        if (!$path) {

            if ($randomVideos === null) {

                $dir = public_path('videos/tenant');

                $randomVideos = collect(
                    glob($dir . '/*.{mp4,webm,ogg}', GLOB_BRACE)
                )
                    ->shuffle()
                    ->values();
            }

            $file = $randomVideos->shift();

            return $file
                ? asset('videos/tenant/' . basename($file))
                : null;
        }

        $path = ltrim($path, '/');

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (file_exists(public_path($path))) {
            return asset($path);
        }

        return Storage::url($path);
    }
}