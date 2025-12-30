<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\Content;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ContentController extends Controller
{
    public function index()
    {
        
        $contents = Content::all();
        return view('tenant.admin.contents.index', compact('contents'));
    }

    public function store(Request $request)
    {
        // ✅ Validation
        $request->validate([
            'type'     => 'required|in:video,pdf,image,audio',
            'filename' => 'required|string|max:255',
            'file'     => 'required|file',
        ]);

        $file = $request->file('file'); // ✅ CORRECT

        // ✅ Allowed mime types
        $allowedMimes = [
            'video' => ['video/mp4', 'video/webm', 'video/ogg'],
            'pdf'   => ['application/pdf'],
            'image' => ['image/jpeg', 'image/png', 'image/webp'],
            'audio' => ['audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/ogg'],
        ];

        if (!in_array($file->getMimeType(), $allowedMimes[$request->type])) {
            return back()->withErrors([
                'file' => 'Invalid file type selected for ' . $request->type
            ]);
        }

        // ✅ Use default disk (local / s3)
        $disk = config('filesystems.default');

        // ✅ Store file (returns STRING path)
        $storedPath = $file->store(
            'uploads/contents/' . $request->type,
            $disk
        );

        // ✅ Public URL (S3 / local compatible)
        $publicPath = Storage::url($storedPath);

        // ✅ Save to DB
        Content::create([
            'type'      => $request->type,
            'filename'  => $request->filename,
            'filepath'  => $publicPath,   // 🔥 STRING, not array
            'user_id'   => Auth::guard('tenant')->user()->id,  // 🔐 secure
            'is_active' => true,
        ]);

        return back()->with('success', 'Content uploaded successfully');
    }


    public function updateContent(Request $request, $id)
    {
        $content = Content::findOrFail($id);

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
    public function destroy($id)
    {
        $content = Content::findOrFail($id);

        // File path stored in DB is already correct (images/xxx.png)
        $path = $content->filepath ?? $content->path ?? null;

        if ($path && Storage::exists($path)) {
            Storage::delete($path);
        }

        $content->delete();

        return back()->with('success', 'Content deleted successfully');
    }

}
