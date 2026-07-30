<?php

namespace App\Http\Controllers\Tenant\Website;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Review;
use App\Models\Tenant\ReviewMedia;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Store customer review submission.
     */
    public function store(Request $request)
    {
        $request->validate([
            'reviewable_type' => 'required|string',
            'reviewable_id'   => 'required|integer',
            'author_name'     => 'required|string|max:255',
            'author_email'    => 'required|email|max:255',
            'rating'          => 'required|integer|min:1|max:5',
            'comment'         => 'required|string|min:5',
        ]);

        $user = auth()->user();

        $review = Review::create([
            'reviewable_type' => $request->reviewable_type,
            'reviewable_id'   => $request->reviewable_id,
            'user_id'         => $user?->id,
            'author_name'     => $request->author_name,
            'author_email'    => $request->author_email,
            'rating'          => (int) $request->rating,
            'title'           => $request->title,
            'comment'         => $request->comment,
            'status'          => 'approved', // Auto-approve or queue for moderation
            'is_verified_buyer' => $user ? true : false,
        ]);

        // Upload media attachments if present
        if ($request->hasFile('review_media')) {
            foreach ($request->file('review_media') as $file) {
                $path = $file->store('reviews', 'public');
                $type = str_contains($file->getMimeType(), 'video') ? 'video' : 'image';
                ReviewMedia::create([
                    'review_id' => $review->id,
                    'file_path' => $path,
                    'file_type' => $type,
                ]);
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for your review!',
                'review'  => $review,
            ]);
        }

        return back()->with('success', 'Thank you! Your review has been submitted.');
    }
}
