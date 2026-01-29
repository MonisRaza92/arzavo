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
        $storageUsed = Content::sum('size');
        return view('tenant.admin.contents.index', compact('contents', 'storageUsed'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'     => 'required|in:video,pdf,image,audio',
            'filename' => 'nullable|string|max:255',
            'file'     => 'required|file',
        ]);

        $file = $request->file('file');

        $allowedMimes = [
            'video' => ['video/mp4', 'video/webm', 'video/ogg'],
            'pdf'   => ['application/pdf'],
            'image' => ['image/jpeg', 'image/png', 'image/webp'],
            'audio' => ['audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/ogg'],
        ];

        if (! in_array($file->getMimeType(), $allowedMimes[$request->type])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid file type',
            ], 422);
        }

        // 🔐 IMMUTABLE TENANT IDENTIFIER
        $tenant = app('currentTenant');
        $tenantId = $tenant->id; // or $tenant->uuid if you have one

        // ✅ PATH ONLY (NO URL)
        $path = $file->store(
            "uploads/tenants/{$tenantId}/contents/{$request->type}",
            config('filesystems.default')
        );
        $size = $file->getSize();

        $filename = $request->filled('filename')
            ? $request->filename
            : pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $content = Content::create([
            'type'      => $request->type,
            'filename'  => $filename,
            'filepath'  => $path, // ✅ ONLY RELATIVE PATH
            'size'      => $size,
            'user_id'   => Auth::guard('tenant')->id(),
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'id'       => $content->id,
                'type'     => $content->type,
                'filename' => $content->filename,
                'size'     => $content->size ?? $size,
                // 👇 frontend ke liye URL yahin generate karo
                'filepath' => $content->filepath,
            ]
        ]);
    }


    public function destroy($id)
    {
        $content = Content::findOrFail($id);

        $disk = Storage::disk(config('filesystems.default'));

        if ($content->filepath && $disk->exists($content->filepath)) {
            $disk->delete($content->filepath);
        }

        $content->delete();

        return back()->with('success', 'Content deleted successfully');
    }
}
