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
        
        // Enforce HTTPS everywhere (both local SSL and production)
        URL::forceScheme('https');
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
                $blogsQuery = \App\Models\Tenant\Blog::published();
                if (request()->has('category') || request()->has('blog_category')) {
                    $val = request('category') ?: request('blog_category');
                    $blogsQuery->where('category', $val);
                }
                $blogs = $blogsQuery->orderBy('created_at', 'desc')->get();
            }

            static $blogCategories = null;
            if ($blogCategories === null && class_exists(\App\Models\Tenant\Blog::class)) {
                $blogCategories = \App\Models\Tenant\Blog::published()
                    ->whereNotNull('category')
                    ->where('category', '!=', '')
                    ->select('category')
                    ->distinct()
                    ->get()
                    ->map(function ($blog) {
                        return (object) [
                            'name' => $blog->category,
                            'slug' => $blog->category,
                            'title' => $blog->category,
                            'image' => null,
                            'description' => 'Browse all articles under ' . $blog->category
                        ];
                    });
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

            static $currentBlog = null;
            static $relatedBlogs = null;

            if ($currentBlog === null && class_exists(\App\Models\Tenant\Blog::class)) {
                if (request()->has('slug') || request()->has('id')) {
                    $val = request('slug') ?: request('id');
                    $query = \App\Models\Tenant\Blog::published()->with(['author']);

                    if (is_numeric(request('id'))) {
                        $currentBlog = (clone $query)->where('id', request('id'))->first();
                    } else {
                        $currentBlog = (clone $query)->where('slug', $val)->first();
                    }
                }

                // 🔥 Fallback to FIRST blog (e.g. for Theme Builder preview)
                if (!$currentBlog) {
                    $currentBlog = \App\Models\Tenant\Blog::published()
                        ->with(['author'])
                        ->first() ?? \App\Models\Tenant\Blog::with(['author'])->first();
                }

                if ($currentBlog) {
                    $relatedBlogs = \App\Models\Tenant\Blog::published()
                        ->where('id', '!=', $currentBlog->id)
                        ->where(function ($q) use ($currentBlog) {
                            if ($currentBlog->category) {
                                $q->orWhere('category', $currentBlog->category);
                            }
                        })
                        ->orderBy('created_at', 'desc')
                        ->take(4)
                        ->get();
                }
            }

            static $currentCourse = null;
            if ($currentCourse === null && class_exists(\App\Models\Tenant\Course::class)) {
                $currentCourse = \App\Models\Tenant\Course::published()->public()->first();
            }

            // 🔥 BUILDER MOCK FALLBACKS (ONLY WHEN isBuilder() IS TRUE & REAL DATA IS EMPTY)
            if (isBuilder()) {
                if (!$currentBook) {
                    $currentBook = (object) [
                        'id'               => 1,
                        'title'            => 'NCERT Physics Class 11 Textbook',
                        'slug'             => 'ncert-physics-class-11',
                        'author'           => 'Monis Raza Khan',
                        'publisher'        => 'NCERT Publishing',
                        'edition'          => '2026 Edition',
                        'isbn'             => '978-3-16-148410-0',
                        'short_description'=> 'Essential physics guide for JEE Main & Advanced aspirants with chapter-wise theory, formula sheets and solved numericals.',
                        'description'      => 'Comprehensive NCERT Physics textbook covering mechanics, thermodynamics, gravitation, and optics for Class 11 and NEET aspirants.',
                        'content'          => 'Detailed study material with step-by-step problem solving techniques, conceptual illustrations, and previous year board questions.',
                        'pages_count'      => 384,
                        'cover_image'      => null,
                        'thumbnail'        => null,
                        'file_path'        => 'sample.pdf',
                        'price_type'       => 'free',
                        'price'            => 0,
                        'sale_price'       => 0,
                        'access_type'      => 'public',
                        'views_count'      => 1284,
                        'downloads_count'  => 450,
                        'created_at'       => now(),
                        'published_at'     => now(),
                        'bookCategory'     => (object) ['id' => 1, 'name' => 'Academic Books', 'slug' => 'academic-books'],
                        'academicCategory' => (object) ['id' => 1, 'name' => 'NEET & JEE', 'slug' => 'neet-jee'],
                        'classCourse'      => (object) ['id' => 1, 'name' => 'Class 11', 'slug' => 'class-11'],
                        'subject'          => (object) ['id' => 1, 'name' => 'Physics', 'slug' => 'physics'],
                        'highlights'       => ['500+ Practice Questions & Solved Examples', 'Comprehensive Syllabus Coverage', 'Chapter-wise Formula Sheets & Quick Notes'],
                    ];
                }

                if (!$currentCourse) {
                    $currentCourse = (object) [
                        'id'            => 1,
                        'title'         => 'Complete Physics & Mechanics Masterclass',
                        'slug'          => 'complete-physics-mechanics-masterclass',
                        'author'        => (object) ['fname' => 'Monis', 'lname' => 'Raza', 'name' => 'Monis Raza', 'profile_picture' => null],
                        'short_description' => 'Master Mechanics, Electrodynamics & Thermodynamics with live interactive lectures, quizzes and doubt solving.',
                        'description'   => 'In-depth video lectures, notes, quizzes, and live doubt-solving sessions for Class 11 & NEET aspirants.',
                        'content'       => 'Comprehensive course covering fundamental and advanced physics concepts with 50+ hours of high-definition video content.',
                        'cover_image'   => null,
                        'thumbnail'     => null,
                        'price_type'    => 'paid',
                        'price'         => 1999,
                        'sale_price'    => 1499,
                        'views_count'   => 1250,
                        'lessons_count' => 48,
                        'duration'      => '32 Hours',
                        'created_at'    => now(),
                        'published_at'  => now(),
                        'category'      => (object) ['id' => 1, 'name' => 'Physics', 'slug' => 'physics'],
                        'classCourse'   => (object) ['id' => 1, 'name' => 'Class 11', 'slug' => 'class-11'],
                    ];
                }

                if (!$currentBlog) {
                    $currentBlog = (object) [
                        'id'          => 1,
                        'title'       => 'Mastering Modern Web Development in 2026',
                        'slug'        => 'mastering-modern-web-development',
                        'author'      => (object) ['fname' => 'Monis', 'lname' => 'Raza', 'name' => 'Monis Raza', 'profile_picture' => null],
                        'short_description' => 'A complete guide to modern web architecture, frontend performance optimization, and scalable backend design.',
                        'description' => 'Comprehensive breakdown of modern web engineering practices including reactive UI components and serverless deployments.',
                        'content'     => 'Full article content goes here. Learn best practices for building responsive, accessible, and fast web applications using modern tooling.',
                        'cover_image' => null,
                        'thumbnail'   => null,
                        'created_at'  => now(),
                        'published_at'=> now(),
                        'views_count' => 540,
                        'read_time'   => '5 min read',
                        'category'    => 'Technology',
                        'tags'        => ['Web Dev', 'Laravel', 'Tailwind'],
                    ];
                }

                if (!$books || $books->isEmpty()) {
                    $books = collect([
                        $currentBook,
                        (object) [
                            'id'               => 2,
                            'title'            => 'Advanced Chemistry for Class 12',
                            'slug'             => 'advanced-chemistry-class-12',
                            'author'           => 'Dr. A. Sharma',
                            'publisher'        => 'Arzavo Press',
                            'edition'          => '2026 Edition',
                            'isbn'             => '978-3-16-148411-7',
                            'short_description'=> 'Complete Organic, Inorganic & Physical Chemistry guide with reaction mechanisms and board exam prep.',
                            'description'      => 'Detailed physical chemistry numericals and organic mechanisms simplified for senior secondary students.',
                            'content'          => 'Step-by-step reaction pathways and previous 10 years solved board papers with model solutions.',
                            'pages_count'      => 410,
                            'cover_image'      => null,
                            'thumbnail'        => null,
                            'file_path'        => 'sample.pdf',
                            'price_type'       => 'paid',
                            'price'            => 599,
                            'sale_price'       => 399,
                            'access_type'      => 'paid',
                            'views_count'      => 890,
                            'downloads_count'  => 310,
                            'created_at'       => now(),
                            'published_at'     => now(),
                            'bookCategory'     => (object) ['id' => 2, 'name' => 'Reference Books', 'slug' => 'reference-books'],
                            'academicCategory' => (object) ['id' => 2, 'name' => 'Board Exams', 'slug' => 'board-exams'],
                            'classCourse'      => (object) ['id' => 2, 'name' => 'Class 12', 'slug' => 'class-12'],
                            'subject'          => (object) ['id' => 2, 'name' => 'Chemistry', 'slug' => 'chemistry'],
                        ],
                        (object) [
                            'id'               => 3,
                            'title'            => 'Mathematics Formula Handbook',
                            'slug'             => 'mathematics-formula-handbook',
                            'author'           => 'R. D. Verma',
                            'publisher'        => 'Arzavo Press',
                            'edition'          => '2026 Edition',
                            'isbn'             => '978-3-16-148412-4',
                            'short_description'=> 'Pocket formula guide covering Calculus, Algebra, Coordinate Geometry and Trigonometry for quick revision.',
                            'description'      => 'All key formulas, theorems, and shortcut tricks bound in a handy reference book.',
                            'content'          => 'Quick revision cheat sheets and memory maps for engineering entrance examinations.',
                            'pages_count'      => 160,
                            'cover_image'      => null,
                            'thumbnail'        => null,
                            'file_path'        => 'sample.pdf',
                            'price_type'       => 'free',
                            'price'            => 0,
                            'sale_price'       => 0,
                            'access_type'      => 'public',
                            'views_count'      => 2150,
                            'downloads_count'  => 1200,
                            'created_at'       => now(),
                            'published_at'     => now(),
                            'bookCategory'     => (object) ['id' => 3, 'name' => 'Handbooks', 'slug' => 'handbooks'],
                            'academicCategory' => (object) ['id' => 1, 'name' => 'NEET & JEE', 'slug' => 'neet-jee'],
                            'classCourse'      => (object) ['id' => 3, 'name' => 'Class 10', 'slug' => 'class-10'],
                            'subject'          => (object) ['id' => 3, 'name' => 'Mathematics', 'slug' => 'mathematics'],
                        ],
                    ]);
                }

                if (!$courses || $courses->isEmpty()) {
                    $courses = collect([
                        $currentCourse,
                        (object) [
                            'id'            => 2,
                            'title'         => 'Organic Chemistry Crash Course 2026',
                            'slug'          => 'organic-chemistry-crash-course',
                            'author'        => (object) ['fname' => 'Dr. A.', 'lname' => 'Sharma', 'name' => 'Dr. A. Sharma', 'profile_picture' => null],
                            'short_description' => 'Fast-track organic chemistry preparation covering reaction mechanisms, named reactions, and practice problems.',
                            'description'   => 'Intensive 20-day crash course covering all high-weightage topics for competitive entrance exams.',
                            'content'       => 'High yield video lectures and downloadable PDF notes.',
                            'cover_image'   => null,
                            'thumbnail'     => null,
                            'price_type'    => 'paid',
                            'price'         => 1499,
                            'sale_price'    => 999,
                            'views_count'   => 920,
                            'lessons_count' => 30,
                            'duration'      => '20 Hours',
                            'created_at'    => now(),
                            'published_at'  => now(),
                            'category'      => (object) ['id' => 2, 'name' => 'Chemistry', 'slug' => 'chemistry'],
                            'classCourse'   => (object) ['id' => 2, 'name' => 'Class 12', 'slug' => 'class-12'],
                        ],
                        (object) [
                            'id'            => 3,
                            'title'         => 'Class 10 Boards Mathematics Mastery',
                            'slug'          => 'class-10-boards-maths',
                            'author'        => (object) ['fname' => 'R. D.', 'lname' => 'Verma', 'name' => 'R. D. Verma', 'profile_picture' => null],
                            'short_description' => 'Master Class 10 board exam mathematics with step-by-step chapter solutions and mock test series.',
                            'description'   => 'Complete syllabus course designed specifically for Class 10 students aiming for 95%+ in boards.',
                            'content'       => 'Chapter-wise video tutorials, sample papers, and board answer sheet presentation tips.',
                            'cover_image'   => null,
                            'thumbnail'     => null,
                            'price_type'    => 'free',
                            'price'         => 0,
                            'sale_price'    => 0,
                            'views_count'   => 1840,
                            'lessons_count' => 60,
                            'duration'      => '45 Hours',
                            'created_at'    => now(),
                            'published_at'  => now(),
                            'category'      => (object) ['id' => 3, 'name' => 'Mathematics', 'slug' => 'maths'],
                            'classCourse'   => (object) ['id' => 3, 'name' => 'Class 10', 'slug' => 'class-10'],
                        ],
                    ]);
                }

                if (!$blogs || $blogs->isEmpty()) {
                    $blogs = collect([
                        $currentBlog,
                        (object) [
                            'id'               => 2,
                            'title'            => 'Top 10 Exam Preparation Tips for 2026',
                            'slug'             => 'top-10-exam-prep-tips',
                            'author'           => (object) ['fname' => 'Team', 'lname' => 'Arzavo', 'name' => 'Team Arzavo', 'profile_picture' => null],
                            'short_description'=> 'Proven strategies and time management hacks to boost your study efficiency and exam scores.',
                            'description'      => 'Detailed article sharing actionable study techniques, memory retention tips, and daily routine schedules.',
                            'content'          => 'Discover top study hacks verified by toppers: active recall, spaced repetition, and exam hall strategy.',
                            'cover_image'      => null,
                            'thumbnail'        => null,
                            'created_at'       => now(),
                            'published_at'     => now(),
                            'views_count'      => 890,
                            'read_time'        => '4 min read',
                            'category'         => 'Study Tips',
                            'tags'             => ['Exam Tips', 'Productivity', 'Study Guide'],
                        ],
                        (object) [
                            'id'               => 3,
                            'title'            => 'Understanding Thermodynamics Concepts',
                            'slug'             => 'thermodynamics-concepts',
                            'author'           => (object) ['fname' => 'Monis', 'lname' => 'Raza', 'name' => 'Monis Raza', 'profile_picture' => null],
                            'short_description'=> 'Simplified explanation of Laws of Thermodynamics, Heat Engines, and Entropy with real-world examples.',
                            'description'      => 'Clear conceptual breakdown of core thermodynamic principles for physics students.',
                            'content'          => 'Explore heat transfers, work done in gas processes, and Carnot engine efficiency calculations.',
                            'cover_image'      => null,
                            'thumbnail'        => null,
                            'created_at'       => now(),
                            'published_at'     => now(),
                            'views_count'      => 620,
                            'read_time'        => '6 min read',
                            'category'         => 'Physics',
                            'tags'             => ['Physics', 'Thermodynamics', 'JEE Prep'],
                        ],
                    ]);
                }

                if (!$bookCategories || $bookCategories->isEmpty()) {
                    $bookCategories = collect([
                        (object) ['id' => 1, 'name' => 'Academic Books', 'slug' => 'academic-books', 'description' => 'Textbooks for Class 9 to 12', 'books_count' => 12, 'icon' => 'fa-book'],
                        (object) ['id' => 2, 'name' => 'Competitive Exams', 'slug' => 'competitive-exams', 'description' => 'Books for JEE, NEET, and CUET', 'books_count' => 8, 'icon' => 'fa-graduation-cap'],
                        (object) ['id' => 3, 'name' => 'Reference Handbooks', 'slug' => 'reference-handbooks', 'description' => 'Quick revision guides and formula sheets', 'books_count' => 5, 'icon' => 'fa-bookmark'],
                    ]);
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
                'blogCategories' => $blogCategories ?? collect(),
                'categories' => $categories,
                'bookCategories' => $bookCategories,
                'books' => $books,
                'currentBook' => $currentBook,
                'currentCourse' => $currentCourse,
                'relatedBooks' => $relatedBooks ?? collect(),
                'currentBlog' => $currentBlog,
                'relatedBlogs' => $relatedBlogs ?? collect(),
            ]);



            return $view;
        });
    }
}