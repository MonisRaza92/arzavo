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
        return view('tenant.admin.blogs.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'heading' => 'nullable|string|max:255',
            'content' => 'nullable|string',

            // image already uploaded elsewhere → just path string
            'featured_image' => 'nullable|string|max:500',
            'image_alt' => 'nullable|string|max:255',

            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',

            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        // auto set publish date if published but no date
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        // author auto attach (optional)
        if (auth()->check()) {
            $data['author_id'] = auth()->id();
        }

        Blog::create($data);
        ping_google();

        return back()->with('success', 'Blog created successfully');
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
        //
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
            'content' => 'nullable|string',

            $imageField => 'nullable|string',

            'image_alt' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        // map dynamic input → DB column
        if ($request->filled($imageField)) {
            $data['featured_image'] = $request->input($imageField);
        }

        // remove dynamic key so mass assignment clean rahe
        unset($data[$imageField]);

        $blog->update($data);
        ping_google();

        return back()->with('success', 'Blog updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();

        $blog->delete();

        return back()->with('success', 'Blog deleted');
    }
}
