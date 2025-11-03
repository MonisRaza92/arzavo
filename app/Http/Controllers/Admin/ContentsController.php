<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Contents;
class ContentsController extends Controller
{
    public function notes()
    {
        return view('admin.notes');
    }
    public function books()
    {
        return view('admin.books');
    }
    public function videos()
    {
        return view('admin.videos');
    }
    public function uploadContent(Request $request)
    {
        // Validate and handle file upload logic here
        $validated = $request->validate([
            'type' => 'required|in:note,book,video',
            'file' => 'required|file', // max 10
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|integer',
            'subject_id' => 'nullable|integer',
            'class_id' => 'nullable|integer',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'is_free' => 'nullable|boolean',
            'duration' => 'nullable|string|max:50',
            'thumbnail' => 'nullable|image|max:2048', // max 2MB    
            'status' => 'required|in:published,draft,archived',
            'user_id' => 'required|exists:users,id',
        ]);
        // Store file and thumbnail, create content record
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('uploads/contents/files', 'public');
            $validated['file'] = 'storage/' . $filePath;
        }
        
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('uploads/contents/thumbnails', 'public');
            $validated['thumbnail'] = 'storage/' . $thumbnailPath;
        }
        // Save content details to database (pseudo-code)
        $content = Contents::create($validated);


        return redirect()->back()->with('success', 'Content uploaded successfully.');
    }

    public function updateContent(Request $request, $id)
    {
        $content = Contents::findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|in:note,book,video',
            'file' => 'nullable|file',
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|integer',
            'subject_id' => 'nullable|integer',
            'class_id' => 'nullable|integer',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'is_free' => 'nullable|boolean',
            'duration' => 'nullable|string|max:50',
            'thumbnail' => 'nullable|image|max:2048', // max 2MB    
            'status' => 'required|in:published,draft,archived',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('uploads/contents/files', 'public');
            $validated['file'] = 'storage/' . $filePath;
        }

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('uploads/contents/thumbnails', 'public');
            $validated['thumbnail'] = 'storage/' . $thumbnailPath;
        }

        $content->update($validated);

        return redirect()->back()->with('success', 'Content updated successfully.');
    }
    public function deleteContent($id)
    {
        $content = Contents::findOrFail($id);

        // Optional: delete associated file if exists
        if ($content->file && file_exists(public_path($content->file))) {
            unlink(public_path($content->file));
        }

        // Optional: delete associated thumbnail if exists
        if ($content->thumbnail && file_exists(public_path($content->thumbnail))) {
            unlink(public_path($content->thumbnail));
        }

        $content->delete();

        return redirect()->back()->with('success', 'Content deleted successfully.');
    }
}
