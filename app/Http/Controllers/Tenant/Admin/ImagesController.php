<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Tenant\Images;
use Illuminate\Support\Facades\Storage;

class ImagesController extends Controller
{
    public function index()
    {
        return view('tenant.admin.images.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120', // 5MB max
        ]);

        // Default disk (local OR s3 automatically)
        $disk = Storage::disk(config('filesystems.default'));

        // Upload to storage
        $filePath = $request->file('image')->store('images');

        // Generate FULL URL (local or s3)
        $fullUrl = Storage::url($filePath);

        // Save to DB
        $image = Images::create([
            'filename' => $request->file('image')->getClientOriginalName(),
            'filepath' => $fullUrl,    // 👈 अब यहीं full URL save होगा
        ]);

        if ($request->ajax()) {
            return response()->json([
                'url' => $fullUrl,
                'message' => 'Image uploaded successfully'
            ]);
        }

        return back()->with('success', 'Selected Image added successfully');
    }


    public function destroy($id)
    {
        $image = Images::findOrFail($id);

        // File path stored in DB is already correct (images/xxx.png)
        $path = $image->filepath ?? $image->path ?? null;

        if ($path && Storage::exists($path)) {
            Storage::delete($path);
        }

        $image->delete();

        return back()->with('success', 'Image deleted successfully');
    }
}
