<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageController
{
    public function index()
    {
        $pages = Page::all();
        return view('admin.pages.index', compact('pages'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9\-]+$/', // sirf lowercase letters, numbers, aur dash
                'unique:pages,slug',
            ],
        ]);

        $page = Page::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'status' => $request->status ? 1 : 0,
        ]);

        return back()->with('success', 'Page created successfully!');
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9\-]+$/', // sirf lowercase letters, numbers, aur dash
                 Rule::unique('pages', 'slug')->ignore($page->id),
            ],
        ]);

        $page->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'status' => $request->status ? 1 : 0,
        ]);

        return back()->with('success', 'Page updated successfully!');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        
        return back()->with('success', 'Page deleted successfully!');
    }
}
