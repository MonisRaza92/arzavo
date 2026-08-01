<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Models\Tenant\Book;
use App\Models\Tenant\BookCategory;
use App\Models\Tenant\AcademicCategory;
use App\Models\Tenant\ClassCourse;
use App\Models\Tenant\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookController
{
    public function index()
    {
        $books = Book::with(['bookCategory', 'academicCategory', 'classCourse', 'subject'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('tenant.admin.library.books.index', compact('books'));
    }

    public function create()
    {
        $bookCategories = BookCategory::where('status', true)->orderBy('order')->get();
        $academicCategories = AcademicCategory::where('status', true)->orderBy('order')->get();
        $classes = ClassCourse::where('status', true)->orderBy('order')->get();
        $subjects = Subject::where('status', true)->orderBy('order')->get();

        return view('tenant.admin.library.books.create', compact(
            'bookCategories',
            'academicCategories',
            'classes',
            'subjects'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'edition' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'highlights' => 'nullable|array',
            'highlights.*' => 'nullable|string',
            'pages_count' => 'nullable|integer|min:0',
            
            // Media paths from Content picker
            'cover_image' => 'nullable|string|max:255',
            'file_path' => 'required|string|max:255',
            
            // Pricing
            'price_type' => 'required|in:free,paid',
            'price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lte:price',
            
            // Access and status
            'access_type' => 'required|in:public,students_only,enrolled_students_only',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            
            // Relationships
            'book_category_id' => 'required|exists:book_categories,id',
            'academic_category_id' => 'nullable|exists:academic_categories,id',
            'class_course_id' => 'nullable|exists:class_courses,id',
            'subject_id' => 'nullable|exists:subjects,id',

            // Preview Images
            'preview_images' => 'nullable|array',
            'preview_images.*' => 'nullable|string',
            'preview_titles' => 'nullable|array',
            'preview_titles.*' => 'nullable|string',
        ]);

        $originalSlug = Str::slug($data['title']);
        $slug = $originalSlug;
        $count = 1;
        while (Book::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        $data['slug'] = $slug;

        $data['price'] = $data['price'] ?? 0.00;
        if (!empty($data['description'])) {
            $data['description'] = htmlspecialchars_decode(htmlspecialchars_decode($data['description'], ENT_QUOTES), ENT_QUOTES);
        }

        if (isset($data['highlights']) && is_array($data['highlights'])) {
            $data['highlights'] = array_values(array_filter($data['highlights'], fn($h) => filled(trim($h))));
        }

        $book = Book::create($data);

        // Sync Preview Images
        if ($request->has('preview_images') && is_array($request->preview_images)) {
            foreach ($request->preview_images as $index => $imgPath) {
                if (filled($imgPath)) {
                    $title = $request->preview_titles[$index] ?? null;
                    $book->previews()->create([
                        'file_path' => $imgPath,
                        'title' => $title,
                        'sort_order' => $index,
                    ]);
                }
            }
        }

        return redirect()->route('admin.books.index')->with('success', 'Book uploaded successfully.');
    }

    public function edit($id)
    {
        $book = Book::with('previews')->findOrFail($id);
        $bookCategories = BookCategory::where('status', true)->orderBy('order')->get();
        $academicCategories = AcademicCategory::where('status', true)->orderBy('order')->get();
        $classes = ClassCourse::where('status', true)->orderBy('order')->get();
        $subjects = Subject::where('status', true)->orderBy('order')->get();

        return view('tenant.admin.library.books.edit', compact(
            'book',
            'bookCategories',
            'academicCategories',
            'classes',
            'subjects'
        ));
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'edition' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'highlights' => 'nullable|array',
            'highlights.*' => 'nullable|string',
            'pages_count' => 'nullable|integer|min:0',
            
            // Media paths
            'cover_image' => 'nullable|string|max:255',
            'file_path' => 'required|string|max:255',
            
            // Pricing
            'price_type' => 'required|in:free,paid',
            'price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lte:price',
            
            // Access
            'access_type' => 'required|in:public,students_only,enrolled_students_only',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            
            // Relationships
            'book_category_id' => 'required|exists:book_categories,id',
            'academic_category_id' => 'nullable|exists:academic_categories,id',
            'class_course_id' => 'nullable|exists:class_courses,id',
            'subject_id' => 'nullable|exists:subjects,id',

            // Preview Images
            'preview_images' => 'nullable|array',
            'preview_images.*' => 'nullable|string',
            'preview_titles' => 'nullable|array',
            'preview_titles.*' => 'nullable|string',
        ]);

        $originalSlug = Str::slug($data['title']);
        $slug = $originalSlug;
        $count = 1;
        while (Book::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        $data['slug'] = $slug;

        $data['price'] = $data['price'] ?? 0.00;
        $data['is_active'] = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');
        if (!empty($data['description'])) {
            $data['description'] = htmlspecialchars_decode(htmlspecialchars_decode($data['description'], ENT_QUOTES), ENT_QUOTES);
        }

        if (isset($data['highlights']) && is_array($data['highlights'])) {
            $data['highlights'] = array_values(array_filter($data['highlights'], fn($h) => filled(trim($h))));
        } else {
            $data['highlights'] = [];
        }

        $book->update($data);

        // Sync Preview Images
        $book->previews()->delete();
        if ($request->has('preview_images') && is_array($request->preview_images)) {
            foreach ($request->preview_images as $index => $imgPath) {
                if (filled($imgPath)) {
                    $title = $request->preview_titles[$index] ?? null;
                    $book->previews()->create([
                        'file_path' => $imgPath,
                        'title' => $title,
                        'sort_order' => $index,
                    ]);
                }
            }
        }

        return redirect()->route('admin.books.index')->with('success', 'Book details updated successfully.');
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Book deleted successfully.');
    }
}
