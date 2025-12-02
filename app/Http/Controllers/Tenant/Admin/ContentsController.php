<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\Contents;
use Illuminate\Support\Facades\Storage;
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
        $validated = $request->validate([
            'type' => 'required|in:note,book,video',
            'file' => 'required|file',
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|integer',
            'subject_id' => 'nullable|integer',
            'class_id' => 'nullable|integer',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'is_free' => 'nullable|boolean',
            'duration' => 'nullable|string|max:50',
            'thumbnail' => 'nullable|image|max:2048',
            'status' => 'required|in:published,draft,archived',
            'user_id' => 'required|exists:users,id',
        ]);

        // Use default disk (local OR s3 depending on .env)
        $disk = Storage::disk(config('filesystems.default'));

        // Upload main file
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('uploads/contents/files');
            $validated['file'] = Storage::url($filePath);
        }

        // Upload thumbnail
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('uploads/contents/thumbnails');
            $validated['thumbnail'] = Storage::url($thumbnailPath);
        }

        // Save to DB
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
            'thumbnail' => 'nullable|image|max:2048',
            'status' => 'required|in:published,draft,archived',
            'user_id' => 'required|exists:users,id',
        ]);

        // default storage disk
        $disk = Storage::disk(config('filesystems.default'));

        // -------- UPDATE FILE ----------
        if ($request->hasFile('file')) {

            // delete old file if exists
            if ($content->file) {
                $oldFile = str_replace(Storage::url(''), '', $content->file);
                if (Storage::exists($oldFile)) Storage::delete($oldFile);
            }

            // store new file
            $filePath = $request->file('file')->store('uploads/contents/files');
            $validated['file'] = Storage::url($filePath);
        }

        // -------- UPDATE THUMBNAIL ----------
        if ($request->hasFile('thumbnail')) {

            // delete old thumbnail if exists
            if ($content->thumbnail) {
                $oldThumbnail = str_replace(Storage::url(''), '', $content->thumbnail);
                if (Storage::exists($oldThumbnail)) Storage::delete($oldThumbnail);
            }

            // store new thumbnail
            $thumbnailPath = $request->file('thumbnail')->store('uploads/contents/thumbnails');
            $validated['thumbnail'] = Storage::url($thumbnailPath);
        }

        // update database
        $content->update($validated);

        return redirect()->back()->with('success', 'Content updated successfully.');
    }

    public function deleteContent($id)
    {
        $content = Contents::findOrFail($id);

        // Get real storage paths (remove S3/local URL prefix)
        $filePath = $content->file ? str_replace(Storage::url(''), '', $content->file) : null;
        $thumbnailPath = $content->thumbnail ? str_replace(Storage::url(''), '', $content->thumbnail) : null;

        // Delete file from storage (S3 OR Local)
        if ($filePath && Storage::exists($filePath)) {
            Storage::delete($filePath);
        }

        // Delete thumbnail from storage (S3 OR Local)
        if ($thumbnailPath && Storage::exists($thumbnailPath)) {
            Storage::delete($thumbnailPath);
        }

        // Delete database record
        $content->delete();

        return redirect()->back()->with('success', 'Content deleted successfully.');
    }
}
