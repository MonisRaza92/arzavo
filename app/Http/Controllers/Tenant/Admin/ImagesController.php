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

        // ✔ Use default disk (local on localhost, S3 on live)
        $disk = Storage::disk(config('filesystems.default'));

        // ✔ Upload file on default disk
        $filePath = $request->file('image')->store('images');

        // ✔ Store in DB
        $image = Images::create([
            'filename' => $request->file('image')->getClientOriginalName(),
            'filepath' => $filePath,
        ]);

        // ✔ Generate correct URL (local or s3)
        $url = Storage::url($filePath);

        if ($request->ajax()) {
            return response()->json([
                'url'    => $url,
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
