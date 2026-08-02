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
                $pages = \App\Models\Tenant\Page::where('is_active', true)
                    ->where('slug', '!=', 'checkout')
                    ->where('slug', 'not like', '%checkout%')
                    ->get(['name', 'slug'])
                    ->map(fn($p) => [
                        'label' => $p->name,
                        'url' => $p->slug === 'home' ? '/' : '/' . $p->slug
                    ])->toArray();
            }

            $courses = [];
            if (class_exists(\App\Models\Tenant\Course::class)) {
                $courses = \App\Models\Tenant\Course::where('status', 'published')
                    ->get(['title', 'slug'])
                    ->map(fn($c) => [
                        'label' => $c->title,
                        'url' => '/course?slug=' . $c->slug
                    ])->toArray();
            }

            $books = [];
            if (class_exists(\App\Models\Tenant\Book::class)) {
                $books = \App\Models\Tenant\Book::where('is_active', true)
                    ->get(['title', 'slug'])
                    ->map(fn($b) => [
                        'label' => $b->title,
                        'url' => '/book?slug=' . $b->slug
                    ])->toArray();
            }

            $bookCategories = [];
            if (class_exists(\App\Models\Tenant\BookCategory::class)) {
                $bookCategories = \App\Models\Tenant\BookCategory::where('status', true)
                    ->get(['name', 'slug'])
                    ->map(fn($bc) => [
                        'label' => $bc->name,
                        'url' => '/books?book_category=' . $bc->slug
                    ])->toArray();
            }

            $academicCategories = [];
            if (class_exists(\App\Models\Tenant\AcademicCategory::class)) {
                $academicCategories = \App\Models\Tenant\AcademicCategory::active()
                    ->get(['name', 'slug'])
                    ->map(fn($ac) => [
                        'label' => $ac->name,
                        'url' => '/courses?category=' . $ac->slug
                    ])->toArray();
            }

            $blogs = [];
            if (class_exists(\App\Models\Tenant\Blog::class)) {
                $blogs = \App\Models\Tenant\Blog::where('status', 'published')
                    ->get(['title', 'slug'])
                    ->map(fn($bl) => [
                        'label' => $bl->title,
                        'url' => '/blog?slug=' . $bl->slug
                    ])->toArray();
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

            /*
            // 💡 Future Expansion: Enable these if you want to show Class Courses & Subjects in the picker
            if (class_exists(\App\Models\Tenant\ClassCourse::class)) {
                $classCourses = \App\Models\Tenant\ClassCourse::where('status', true)->get(['name', 'slug']);
                if (count($classCourses)) {
                    $cachedUrls['Course Classes'] = $classCourses->map(fn($cc) => [
                        'label' => $cc->name,
                        'url' => '/courses?class=' . $cc->slug
                    ])->toArray();
                }
            }
            if (class_exists(\App\Models\Tenant\Subject::class)) {
                $subjects = \App\Models\Tenant\Subject::where('status', true)->get(['name', 'slug']);
                if (count($subjects)) {
                    $cachedUrls['Course Subjects'] = $subjects->map(fn($s) => [
                        'label' => $s->name,
                        'url' => '/courses?subject=' . $s->slug
                    ])->toArray();
                }
            }
            */
        }

        $this->urls = $cachedUrls;
    }

    public function render(): View|Closure|string
    {
        return view('components.input.url');
    }
}