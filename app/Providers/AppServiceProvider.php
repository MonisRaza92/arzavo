<?php

namespace App\Providers;

use Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

use App\Models\Arzavo\Tenant;
use App\Observers\TenantObserver;
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Tenant::observe(TenantObserver::class);
        
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
        view()->composer('*', function ($view) {

            if (!app()->bound('currentTenant')) {
                return $view->with([
                    'settings' => [],
                    'customizes' => [],
                    'user' => $user = Auth::guard('web')->user() ?? null,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | REQUEST MEMORY CACHE (RUNS ONLY ONCE PER REQUEST)
            |--------------------------------------------------------------------------
            */

            static $settings = null;
            static $customizes = null;
            static $schemes = null;
            static $menus = null;
            static $user = null;
            static $courses = null;
            static $classes = null;
            static $blogs = null;
            static $categories = null;
            static $bookCategories = null;

            if ($settings === null) {
                $settings = \App\Models\Tenant\Settings
                    ::pluck('value', 'key')
                    ->toArray();
            }

            if ($customizes === null) {
                $customizes = \App\Models\Tenant\Customizes
                    ::pluck('value', 'key')
                    ->toArray();
            }

            if ($schemes === null) {

                $themeId = activeThemeId();

                $schemes = \App\Models\Tenant\ColorScheme
                    ::where('theme_id', $themeId)
                    ->orderBy('created_at', 'asc')
                    ->get()
                    ->keyBy('key');
            }

            if ($menus === null) {
                $menus = \App\Models\Tenant\Menu::all();
            }

            if ($user === null) {
                $user = Auth::guard('tenant')->user() ?? null;
            }

            if ($courses === null && class_exists(\App\Models\Tenant\Course::class)) {
                $coursesQuery = \App\Models\Tenant\Course::published()->public();
                if (request()->has('class_id') || request()->has('class')) {
                    $coursesQuery->whereHas('classes', function ($q) {
                        $val = request('class_id') ?: request('class');
                        if (is_numeric($val)) {
                            $q->where('class_courses.id', $val);
                        } else {
                            $q->where('class_courses.slug', $val);
                        }
                    });
                }
                if (request()->has('subject_id') || request()->has('subject')) {
                    $coursesQuery->whereHas('subjects', function ($q) {
                        $val = request('subject_id') ?: request('subject');
                        if (is_numeric($val)) {
                            $q->where('subjects.id', $val);
                        } else {
                            $q->where('subjects.slug', $val);
                        }
                    });
                }
                if (request()->has('category_id') || request()->has('category')) {
                    $coursesQuery->whereHas('classes', function ($q) {
                        $val = request('category_id') ?: request('category');
                        if (is_numeric($val)) {
                            $q->where('class_courses.academic_category_id', $val);
                        } else {
                            $q->whereHas('academicCategory', function($acq) use ($val) {
                                $acq->where('slug', $val);
                            });
                        }
                    });
                }
                $courses = $coursesQuery->orderBy('created_at', 'desc')->get();
            }

            if ($classes === null && class_exists(\App\Models\Tenant\ClassCourse::class)) {
                $classesQuery = \App\Models\Tenant\ClassCourse::active();
                if (request()->has('category_id') || request()->has('category')) {
                    $val = request('category_id') ?: request('category');
                    if (is_numeric($val)) {
                        $classesQuery->where('academic_category_id', $val);
                    } else {
                        $classesQuery->whereHas('academicCategory', function($acq) use ($val) {
                            $acq->where('slug', $val);
                        });
                    }
                }
                $classes = $classesQuery->orderBy('order')->get();
            }

            if ($blogs === null && class_exists(\App\Models\Tenant\Blog::class)) {
                $blogs = \App\Models\Tenant\Blog::published()->orderBy('created_at', 'desc')->get();
            }

            if ($categories === null && class_exists(\App\Models\Tenant\AcademicCategory::class)) {
                $categories = \App\Models\Tenant\AcademicCategory::active()
                    ->with(['classCourses' => function ($q) {
                        $q->active()->with(['subjects' => function ($sq) {
                            $sq->active();
                        }]);
                    }])
                    ->orderBy('order')
                    ->get();
            }

            if ($bookCategories === null && class_exists(\App\Models\Tenant\BookCategory::class)) {
                $bookCategories = \App\Models\Tenant\BookCategory::where('status', true)
                    ->orderBy('order')
                    ->get();
            }

            static $books = null;

            if ($books === null && class_exists(\App\Models\Tenant\Book::class)) {
                $booksQuery = \App\Models\Tenant\Book::where('is_active', true);
                if (request()->has('book_category') || request()->has('book_category_id')) {
                    $val = request('book_category') ?: request('book_category_id');
                    if (is_numeric($val)) {
                        $booksQuery->where('book_category_id', $val);
                    } else {
                        $booksQuery->whereHas('bookCategory', function ($q) use ($val) {
                            $q->where('slug', $val);
                        });
                    }
                }
                $books = $booksQuery->orderBy('created_at', 'desc')->get();
            }

            static $currentBook = null;
            static $relatedBooks = null;

            if ($currentBook === null && class_exists(\App\Models\Tenant\Book::class)) {
                if (request()->has('slug') || request()->has('id')) {
                    $val = request('slug') ?: request('id');
                    $query = \App\Models\Tenant\Book::where('is_active', true)
                        ->with(['bookCategory', 'academicCategory', 'classCourse', 'subject']);

                    if (is_numeric(request('id'))) {
                        $currentBook = (clone $query)->where('id', request('id'))->first();
                    } else {
                        $currentBook = (clone $query)->where('slug', $val)->first();
                    }

                    if ($currentBook) {
                        try {
                            $currentBook->increment('views_count');
                        } catch (\Exception $e) {}
                    }
                }

                // 🔥 Fallback to FIRST book (e.g. for Theme Builder preview)
                if (!$currentBook) {
                    $currentBook = \App\Models\Tenant\Book::where('is_active', true)
                        ->with(['bookCategory', 'academicCategory', 'classCourse', 'subject'])
                        ->first() ?? \App\Models\Tenant\Book::with(['bookCategory', 'academicCategory', 'classCourse', 'subject'])->first();
                }

                if ($currentBook) {
                    $relatedBooks = \App\Models\Tenant\Book::where('is_active', true)
                        ->where('id', '!=', $currentBook->id)
                        ->where(function ($q) use ($currentBook) {
                            if ($currentBook->book_category_id) {
                                $q->orWhere('book_category_id', $currentBook->book_category_id);
                            }
                            if ($currentBook->academic_category_id) {
                                $q->orWhere('academic_category_id', $currentBook->academic_category_id);
                            }
                        })
                        ->orderBy('created_at', 'desc')
                        ->take(4)
                        ->get();
                }
            }

            \View::share([
                'settings' => $settings,
                'customizes' => $customizes,
                'colorSchemes' => $schemes,
                'menus' => $menus,
                'user' => $user,
                'courses' => $courses,
                'classes' => $classes,
                'blogs' => $blogs,
                'categories' => $categories,
                'bookCategories' => $bookCategories,
                'books' => $books,
                'currentBook' => $currentBook,
                'relatedBooks' => $relatedBooks ?? collect(),
            ]);


            return $view;
        });
    }
}