<?php
namespace App\View\Components\input;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Tenant\Page;

class url extends Component
{
    public $urls;

    public function __construct()
    {
        /*
        |--------------------------------------------------------------------------
        | REQUEST LEVEL CACHE
        |--------------------------------------------------------------------------
        */

        static $cachedUrls = null;

        if ($cachedUrls === null) {
            $pages = [];
            if (class_exists(\App\Models\Tenant\Page::class)) {
                try {
                    $pages = \App\Models\Tenant\Page::where('is_active', true)
                        ->where('slug', '!=', 'checkout')
                        ->where('slug', 'not like', '%checkout%')
                        ->get(['name', 'slug'])
                        ->map(fn($p) => [
                            'label' => $p->name,
                            'url' => $p->slug === 'home' ? '/' : '/' . $p->slug
                        ])->toArray();
                } catch (\Throwable $e) {
                    $pages = [];
                }
            }

            $courses = [];
            if (class_exists(\App\Models\Tenant\Course::class)) {
                try {
                    $courses = \App\Models\Tenant\Course::where('status', 'published')
                        ->get(['title', 'slug'])
                        ->map(fn($c) => [
                            'label' => $c->title,
                            'url' => '/course?slug=' . $c->slug
                        ])->toArray();
                } catch (\Throwable $e) {
                    $courses = [];
                }
            }

            $books = [];
            if (class_exists(\App\Models\Tenant\Book::class)) {
                try {
                    $books = \App\Models\Tenant\Book::where('is_active', true)
                        ->get(['title', 'slug'])
                        ->map(fn($b) => [
                            'label' => $b->title,
                            'url' => '/book?slug=' . $b->slug
                        ])->toArray();
                } catch (\Throwable $e) {
                    $books = [];
                }
            }

            $bookCategories = [];
            if (class_exists(\App\Models\Tenant\BookCategory::class)) {
                try {
                    $bookCategories = \App\Models\Tenant\BookCategory::where('status', true)
                        ->get(['name', 'slug'])
                        ->map(fn($bc) => [
                            'label' => $bc->name,
                            'url' => '/books?book_category=' . $bc->slug
                        ])->toArray();
                } catch (\Throwable $e) {
                    $bookCategories = [];
                }
            }

            $academicCategories = [];
            if (class_exists(\App\Models\Tenant\AcademicCategory::class)) {
                try {
                    $academicCategories = \App\Models\Tenant\AcademicCategory::active()
                        ->get(['name', 'slug'])
                        ->map(fn($ac) => [
                            'label' => $ac->name,
                            'url' => '/courses?category=' . $ac->slug
                        ])->toArray();
                } catch (\Throwable $e) {
                    $academicCategories = [];
                }
            }

            $blogs = [];
            if (class_exists(\App\Models\Tenant\Blog::class)) {
                try {
                    $blogs = \App\Models\Tenant\Blog::where('status', 'published')
                        ->get(['title', 'slug'])
                        ->map(fn($bl) => [
                            'label' => $bl->title,
                            'url' => '/blog?slug=' . $bl->slug
                        ])->toArray();
                } catch (\Throwable $e) {
                    $blogs = [];
                }
            }

            $cachedUrls = [];

            if (count($pages)) {
                $cachedUrls['Pages'] = $pages;
            }
            if (count($courses)) {
                $cachedUrls['Courses'] = $courses;
            }
            if (count($books)) {
                $cachedUrls['Books'] = $books;
            }
            if (count($bookCategories)) {
                $cachedUrls['Book Categories'] = $bookCategories;
            }
            if (count($academicCategories)) {
                $cachedUrls['Course Categories'] = $academicCategories;
            }
            if (count($blogs)) {
                $cachedUrls['Blog Posts'] = $blogs;
            }
        }

        $this->urls = $cachedUrls;
    }

    public function render(): View|Closure|string
    {
        return view('components.input.url');
    }
}