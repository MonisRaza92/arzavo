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
        
        $disk = Storage::disk(config('filesystems.default'));
        dd($disk);


        // Use putFile (NOT put), and proper options format
        $filePath = $disk->putFile(
            'images',
            $request->file('image'),
            ['visibility' => 'public']
        );

        // If upload failed
        if (!$filePath) {
            return response()->json([
                'error' => 'Upload failed — S3 did not return file path.'
            ], 500);
        }

        // VERY IMPORTANT → Use same disk to generate URL
        $fullUrl = $disk->url($filePath);

        Images::create([
            'filename' => $request->file('image')->getClientOriginalName(),
            'filepath' => $filePath,
        ]);

        return [
            'url' => $fullUrl,
            'message' => 'Image uploaded successfully'
        ];
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
