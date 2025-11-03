<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Images;
use Illuminate\Support\Facades\Storage;

class ImagesController extends Controller
{
    public function index()
    {
        return view('admin.images.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120', // 5MB max
        ]);

        if (!Storage::disk('public')->exists('images')) {
            Storage::disk('public')->makeDirectory('images');
        }
        $filePath = $request->file('image')->store('images', 'public');

        $image = Images::create([
            'filename' => $request->file('image')->getClientOriginalName(),
            'filepath' => 'storage/' . $filePath,
        ]);

        return back()->with('success', 'Image added successfully');
    }

    public function destroy($id)
    {
        $image = Images::findOrFail($id);
        if (Storage::disk('public')->exists(str_replace('storage/', '', $image->path))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $image->path));
        }
        $image->delete();

        return back()->with('success', 'Image deleted successfully');
    }

}
