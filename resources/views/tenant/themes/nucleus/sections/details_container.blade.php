@php
    $source = $section->dataset_source ?? 'book';

    // Resolve data from available view variables
    $data = ${$source} 
        ?? $currentBook 
        ?? $currentBlog 
        ?? $currentCourse 
        ?? $currentPost 
        ?? $book 
        ?? $course 
        ?? $blog 
        ?? $post 
        ?? null;

    // Fallback mock data for Theme Builder preview mode
    if (!$data) {
        if ($source === 'blog' || $source === 'post') {
            $data = (object) [
                'id' => 0,
                'title' => 'Mastering Modern Web Development in 2026',
                'slug' => 'mastering-modern-web-development',
                'author' => (object) ['fname' => 'Monis', 'lname' => 'Raza', 'profile_picture' => null],
                'description' => 'A complete guide to modern web architecture, frontend performance optimization, and scalable backend design.',
                'content' => 'Full article content goes here. Learn best practices for building responsive, accessible, and fast web applications using modern tooling.',
                'cover_image' => null,
                'thumbnail' => null,
                'created_at' => now(),
                'views_count' => 540,
                'category' => (object) ['name' => 'Technology', 'slug' => 'technology'],
                'tags' => ['Web Dev', 'Laravel', 'Tailwind']
            ];
        } elseif ($source === 'course') {
            $data = (object) [
                'id' => 0,
                'title' => 'Complete Physics & Mechanics Masterclass',
                'slug' => 'complete-physics-mechanics-masterclass',
                'author' => (object) ['fname' => 'Monis', 'lname' => 'Raza', 'profile_picture' => null],
                'description' => 'In-depth video lectures, notes, quizzes, and live doubt-solving sessions for Class 11 & NEET aspirants.',
                'cover_image' => null,
                'thumbnail' => null,
                'price_type' => 'paid',
                'price' => 1999,
                'sale_price' => 1499,
                'views_count' => 1250,
                'lessons_count' => 48,
                'duration' => '32 Hours',
                'category' => (object) ['name' => 'Physics', 'slug' => 'physics'],
                'classCourse' => (object) ['name' => 'Class 11']
            ];
        } else { // Default book mock
            $data = (object) [
                'id' => 0,
                'title' => 'NCERT Physics Class 11 Textbook',
                'slug' => 'ncert-physics-class-11',
                'author' => 'Monis Raza Khan',
                'publisher' => 'NCERT Publishing',
                'edition' => '2026 Edition',
                'isbn' => '978-3-16-148410-0',
                'description' => 'Comprehensive NCERT Physics textbook covering mechanics, thermodynamics, oscillations, and wave motion. Designed specifically for Class 11 and competitive exam preparations like NEET and JEE.',
                'pages_count' => 384,
                'cover_image' => null,
                'file_path' => 'sample.pdf',
                'preview_file_path' => 'sample.pdf',
                'price_type' => 'free',
                'price' => 0,
                'sale_price' => 0,
                'access_type' => 'public',
                'views_count' => 1284,
                'downloads_count' => 450,
                'bookCategory' => (object) ['name' => 'Academic Books', 'slug' => 'academic-books'],
                'academicCategory' => (object) ['name' => 'NEET'],
                'classCourse' => (object) ['name' => 'Class 11'],
                'subject' => (object) ['name' => 'Physics']
            ];
        }
    }

@endphp

<section {!! $section->attributes() !!} class="arz-section relative overflow-hidden {{ $section->visibility }}"
    style="{{ $section->margin . $section->padding }}">
    <div
        class="section-content {{ $section->container }} flex {{ $section->direction === 'vertical' ? 'flex-col' : 'lg:flex-row flex-col' }} relative z-30" style="gap:{{ $section->gap }}px;">
        {!! $section->blocks()->render(['data' => $data, 'datasetKey' => $source]) !!}
    </div>
</section>
