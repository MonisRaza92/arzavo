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
            'image' => 'required|image|max:5120',
        ]);

        // store file → RETURNS PATH
        $path = $request->file('image')->store('images');

        // ✅ DB me sirf path save karo
        $image = Images::create([
            'filename' => $request->file('image')->getClientOriginalName(),
            'filepath' => $path, // ✅ CORRECT
        ]);

        if ($request->ajax()) {
            return response()->json([
                'path' => $path,
                'url'  => Storage::url($path), // frontend ke liye
                'message' => 'Image uploaded successfully'
            ]);
        }

        return back()->with('success', 'Image uploaded successfully');
    }

    public function destroy($id)
    {
        $image = Images::findOrFail($id);

        $path = $image->filepath;

        $disk = Storage::disk(config('filesystems.default'));

        if ($path && $disk->exists($path)) {
            $disk->delete($path);
        }

        $image->delete();

        return back()->with('success', 'Image deleted successfully');
    }
}
