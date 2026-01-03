<?php
use Illuminate\Support\Facades\Storage;

if (! function_exists('media')) {

    /**
     * Get public URL for any stored media (local / s3 compatible)
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

        $disk = config('filesystems.default', 'local');

        $path = ltrim($path, '/');

        // Signed URL support (S3)
        if ($signed && method_exists(Storage::disk($disk), 'temporaryUrl')) {
            return Storage::temporaryUrl(
                $path,
                now()->addMinutes(10)
            );
        }
        return Storage::url($path);
    }
}
