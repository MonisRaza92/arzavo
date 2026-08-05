<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Http\Request;
use App\Models\Tenant\Blog;

class BlogController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::orderBy('created_at', 'desc')->get();
        return view('tenant.admin.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tenant.admin.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'heading' => 'nullable|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'category' => 'nullable|string|max:255',

            // image already uploaded elsewhere → just path string
            'featured_image' => 'nullable|string|max:500',
            'image_alt' => 'nullable|string|max:255',

            'status' => 'required|in:draft,published',

            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        // auto set publish date if published at creation time
        if ($data['status'] === 'published') {
            $data['published_at'] = now();
        } else {
            $data['published_at'] = null;
        }

        // author auto attach (optional)
        if (auth()->check()) {
            $data['author_id'] = auth()->id();
        }

        Blog::create($data);
        ping_google();

        return redirect()->route('admin.blog.index')->with('success', 'Blog created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $blog)
    {
        $blog = Blog::where('slug', $blog)->firstOrFail();
        return view('tenant.admin.blogs.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();

        // dynamic field name
        $imageField = 'featured_image_' . $blog->id;

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'heading' => 'nullable|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'category' => 'nullable|string|max:255',

            $imageField => 'nullable|string',

            'image_alt' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        // if changing status to published, record the publish date
        if ($data['status'] === 'published') {
            if ($blog->status !== 'published') {
                $data['published_at'] = now();
            }
        } else {
            $data['published_at'] = null;
        }

        // map dynamic input → DB column
        if ($request->filled($imageField)) {
            $data['featured_image'] = $request->input($imageField);
        }

        // remove dynamic key so mass assignment clean rahe
        unset($data[$imageField]);

        $blog->update($data);
        ping_google();

        return redirect()->route('admin.blog.index')->with('success', 'Blog updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();

        $blog->delete();

        return redirect()->route('admin.blog.index')->with('success', 'Blog deleted');
    }
}

