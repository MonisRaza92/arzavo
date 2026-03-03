<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Support\Carbon;

class SitemapController
{
    public function __invoke()
    {
        $urls = [];
        $base = rtrim(tenant_url(), '/');

        /*
        |--------------------------------------------------------------------------
        | Helper → push url safely
        |--------------------------------------------------------------------------
        */
        $add = function (string $loc, string $priority = '0.7', string $freq = 'weekly', $lastmod = null) use (&$urls) {

            $urls[$loc] = [
                'loc' => $loc,
                'priority' => $priority,
                'changefreq' => $freq,
                'lastmod' => $lastmod
                    ? Carbon::parse($lastmod)->toAtomString()
                    : now()->toAtomString(),
            ];
        };

        /*
        |--------------------------------------------------------------------------
        | Homepage
        |--------------------------------------------------------------------------
        */
        $add($base, '1.0', 'daily');

        /*
        |--------------------------------------------------------------------------
        | Static System Pages
        |--------------------------------------------------------------------------
        */
        foreach ([
            'about',
            'contact',
            'courses',
        ] as $page) {

            $add("$base/$page", '0.8');
        }

        /*
        |--------------------------------------------------------------------------
        | CMS Pages
        |--------------------------------------------------------------------------
        */
        if (class_exists(\App\Models\Page::class)) {

            \App\Models\Page::query()
                ->where('status', 'published')
                ->select('slug', 'updated_at')
                ->chunk(100, function ($pages) use ($base, $add) {

                    foreach ($pages as $page) {
                        $add(
                            "$base/{$page->slug}",
                            '0.7',
                            'weekly',
                            $page->updated_at
                        );
                    }
                });
        }

        /*
        |--------------------------------------------------------------------------
        | Courses
        |--------------------------------------------------------------------------
        */
        if (class_exists(\App\Models\Course::class)) {

            \App\Models\Course::query()
                ->where('status', 'published')
                ->select('slug', 'updated_at')
                ->chunk(100, function ($courses) use ($base, $add) {

                    foreach ($courses as $course) {
                        $add(
                            "$base/view/course/{$course->slug}",
                            '0.8',
                            'weekly',
                            $course->updated_at
                        );
                    }
                });
        }

        /*
        |--------------------------------------------------------------------------
        | Blogs
        |--------------------------------------------------------------------------
        */
        if (class_exists(\App\Models\Blog::class)) {

            \App\Models\Blog::query()
                ->where('status', 'published')
                ->select('slug', 'updated_at')
                ->chunk(100, function ($blogs) use ($base, $add) {

                    foreach ($blogs as $blog) {
                        $add(
                            "$base/blog/{$blog->slug}",
                            '0.7',
                            'weekly',
                            $blog->updated_at
                        );
                    }
                });
        }

        /*
        |--------------------------------------------------------------------------
        | Public Contents (optional module)
        |--------------------------------------------------------------------------
        */
        if (class_exists(\App\Models\Content::class)) {

            \App\Models\Content::query()
                ->where('status', 'published')
                ->select('slug', 'updated_at')
                ->chunk(100, function ($contents) use ($base, $add) {

                    foreach ($contents as $content) {
                        $add(
                            "$base/content/{$content->slug}",
                            '0.6',
                            'monthly',
                            $content->updated_at
                        );
                    }
                });
        }

        /*
        |--------------------------------------------------------------------------
        | Response XML
        |--------------------------------------------------------------------------
        */
        return response()
            ->view('tenant.sitemap', [
                'urls' => array_values($urls)
            ])
            ->header('Content-Type', 'text/xml');
    }
}