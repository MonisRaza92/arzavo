<?php

namespace App\Services\Theme;

use Illuminate\Support\Facades\Storage;

/**
 * ThemeAssetResolver
 * 
 * Resolves and loads theme assets (CSS, JS, images) regardless of
 * whether the theme is a local system theme or an uploaded theme.
 * 
 * Local themes:    resources/views/tenant/themes/{slug}/assets/
 * Uploaded themes: storage/app/themes/{slug}/assets/  (future)
 */
class ThemeAssetResolver
{
    /**
     * Get the raw CSS content for a theme (legacy alias)
     */
    public static function css(string $slug): ?string
    {
        return self::allCss($slug);
    }

    /**
     * Get the raw JS content for a theme (legacy alias)
     */
    public static function js(string $slug): ?string
    {
        return self::allJs($slug);
    }

    /**
     * Get all CSS files from assets/css directory
     */
    public static function cssFiles(string $slug): array
    {
        return self::scanAssetsDirectory($slug, 'assets/css', 'css');
    }

    /**
     * Get all JS files from assets/js directory
     */
    public static function jsFiles(string $slug): array
    {
        return self::scanAssetsDirectory($slug, 'assets/js', 'js');
    }

    /**
     * Load all CSS content for a theme (concatenated)
     */
    public static function allCss(string $slug): string
    {
        $output = '';

        foreach (self::cssFiles($slug) as $file) {
            $content = self::loadAsset($slug, $file);
            if ($content) {
                $output .= "\n/* === {$file} === */\n" . $content;
            }
        }

        return $output;
    }

    /**
     * Load all JS content for a theme (concatenated)
     */
    public static function allJs(string $slug): string
    {
        $output = '';

        foreach (self::jsFiles($slug) as $file) {
            $content = self::loadAsset($slug, $file);
            if ($content) {
                $output .= "\n// === {$file} ===\n" . $content;
            }
        }

        return $output;
    }

    /**
     * Read the theme.json manifest
     */
    public static function manifest(string $slug): array
    {
        static $cache = [];

        if (isset($cache[$slug])) {
            return $cache[$slug];
        }

        $path = self::resolveBasePath($slug) . '/theme.json';

        if (file_exists($path)) {
            $cache[$slug] = json_decode(file_get_contents($path), true) ?? [];
        } else {
            $cache[$slug] = [];
        }

        return $cache[$slug];
    }

    /**
     * Get engine version from manifest
     */
    public static function engineVersion(string $slug): string
    {
        return self::manifest($slug)['engine_version'] ?? '1.0';
    }

    /**
     * Check if theme supports a feature
     */
    public static function supports(string $slug, string $feature): bool
    {
        $supports = self::manifest($slug)['supports'] ?? [];
        return in_array($feature, $supports, true);
    }

    /* --------------------------------
       INTERNAL HELPERS
    -------------------------------- */

    /**
     * Load a single asset file from the theme
     */
    protected static function loadAsset(string $slug, string $relativePath): ?string
    {
        // Normalize path separators
        $relativePath = ltrim(str_replace('\\', '/', $relativePath), '/');

        // 1. Try local theme path (system themes)
        $localPath = self::localBasePath($slug) . '/' . $relativePath;

        if (file_exists($localPath)) {
            $content = file_get_contents($localPath);
            return ($content !== false && trim($content) !== '') ? $content : null;
        }

        // 2. Try uploaded theme path (future: storage-based themes)
        $storagePath = self::uploadedBasePath($slug) . '/' . $relativePath;

        if (file_exists($storagePath)) {
            $content = file_get_contents($storagePath);
            return ($content !== false && trim($content) !== '') ? $content : null;
        }

        return null;
    }

    /**
     * Resolve the base path for a theme (tries local first, then uploaded)
     */
    protected static function resolveBasePath(string $slug): string
    {
        $local = self::localBasePath($slug);
        if (is_dir($local)) {
            return $local;
        }

        $uploaded = self::uploadedBasePath($slug);
        if (is_dir($uploaded)) {
            return $uploaded;
        }

        return $local; // fallback
    }

    /**
     * Local system theme base path
     */
    protected static function localBasePath(string $slug): string
    {
        return resource_path("views/tenant/themes/{$slug}");
    }

    /**
     * Uploaded theme base path (future)
     */
    protected static function uploadedBasePath(string $slug): string
    {
        return storage_path("app/themes/{$slug}");
    }

    /**
     * Scan a directory inside the theme and return all files matching the extension
     */
    protected static function scanAssetsDirectory(string $slug, string $dirPath, string $extension): array
    {
        $basePath = self::resolveBasePath($slug);
        $fullPath = rtrim($basePath, '/') . '/' . trim($dirPath, '/');
        
        $files = [];

        if (is_dir($fullPath)) {
            $iterator = new \FilesystemIterator($fullPath);
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === $extension) {
                    $files[] = trim($dirPath, '/') . '/' . $file->getFilename();
                }
            }
        }
        
        // Also support fallback global.css / global.js in assets folder (legacy/default)
        if (empty($files) && file_exists($basePath . '/assets/global.' . $extension)) {
            $files[] = 'assets/global.' . $extension;
        }

        return $files;
    }
}
