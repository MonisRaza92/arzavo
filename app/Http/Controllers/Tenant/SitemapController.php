<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Support\Carbon;

class SitemapController
{
    public function __invoke()
    {
        $settings = \App\Models\Tenant\Settings::pluck('value', 'key')->toArray();
        if (!($settings['sitemap_enabled'] ?? 1)) {
            abort(404, 'Sitemap is disabled.');
        }

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
        | 1. Homepage
        |--------------------------------------------------------------------------
        */
        $add($base, '1.0', 'daily');

        /*
        |--------------------------------------------------------------------------
        | 2. Static / System Pages
        |--------------------------------------------------------------------------
        */
        foreach ([
            'courses',
            'books',
            'book-categories',
            'blogs',
            'contact',
            'privacy-policy',
            'terms-conditions',
        ] as $page) {
            $add(route_to($page), '0.8', 'weekly');
        }

        /*
        |--------------------------------------------------------------------------
        | 3. CMS Pages
        |--------------------------------------------------------------------------
        */
        if (class_exists(\App\Models\Page::class)) {
            \App\Models\Page::query()
                ->where('status', 'published')
                ->where('is_system_page', false)
                ->select('slug', 'updated_at')
                ->chunk(100, function ($pages) use ($add) {
                    foreach ($pages as $page) {
                        $add(
                            route_to($page->slug),
                            '0.7',
                            'weekly',
                            $page->updated_at
                        );
                    }
                });
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Courses
        |--------------------------------------------------------------------------
        */
        if (class_exists(\App\Models\Course::class)) {
            \App\Models\Course::query()
                ->where('status', 'published')
                ->select('slug', 'updated_at')
                ->chunk(100, function ($courses) use ($add) {
                    foreach ($courses as $course) {
                        $add(
                            route_to('course', $course->slug),
                            '0.8',
                            'weekly',
                            $course->updated_at
                        );
                    }
                });
        }

        /*
        |--------------------------------------------------------------------------
        | 5. Books
        |--------------------------------------------------------------------------
        */
        if (class_exists(\App\Models\Tenant\Book::class)) {
            \App\Models\Tenant\Book::query()
                ->where('is_active', true)
                ->select('slug', 'updated_at')
                ->chunk(100, function ($books) use ($add) {
                    foreach ($books as $book) {
                        $add(
                            route_to('book', $book->slug),
                            '0.8',
                            'weekly',
                            $book->updated_at
                        );
                    }
                });
        }

        /*
        |--------------------------------------------------------------------------
        | 6. Book Categories
        |--------------------------------------------------------------------------
        */
        if (class_exists(\App\Models\Tenant\BookCategory::class)) {
            \App\Models\Tenant\BookCategory::query()
                ->where('status', true)
                ->select('slug', 'updated_at')
                ->chunk(100, function ($cats) use ($add) {
                    foreach ($cats as $cat) {
                        $add(
                            route_to('book.category', $cat->slug),
                            '0.7',
                            'weekly',
                            $cat->updated_at
                        );
                    }
                });
        }

        /*
        |--------------------------------------------------------------------------
        | 7. Blogs
        |--------------------------------------------------------------------------
        */
        if (class_exists(\App\Models\Blog::class)) {
            \App\Models\Blog::query()
                ->where('status', 'published')
                ->select('slug', 'updated_at')
                ->chunk(100, function ($blogs) use ($add) {
                    foreach ($blogs as $blog) {
                        $add(
                            route_to('blog', $blog->slug),
                            '0.7',
                            'weekly',
                            $blog->updated_at
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