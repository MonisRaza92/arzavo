<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Subjects;
use App\Models\Classes;


class ProfileController extends Controller
{
     public function profile()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }   
        $classes = Classes::orderBy('name')->get();
        $subjects = Subjects::all();
        return view('auth.profile', compact('classes', 'subjects'));
    }
    public function profileInfoUpdate(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Validation rules
        $rules = [
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'username' => 'required|string|max:100',
            'headline' => 'nullable|string|max:100',
            'number' => 'required|string|max:15|unique:users,number,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'dob' => 'nullable|date',
            'class_id' => 'nullable',
            'subject_id' => 'nullable',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:20',
            'about' => 'nullable|string|max:300',
        ];

        // If password provided, add validation
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
            // confirmed => check karega `password_confirmation` field se
        }

        $validated = $request->validate($rules);

        // Update basic fields
        $user->fname      = $validated['fname'] ?? $user->fname;
        $user->lname      = $validated['lname'] ?? $user->lname;
        $user->username   = $validated['username'] ?? $user->username;
        $user->headline   = $validated['headline'] ?? $user->headline;
        $user->number     = $validated['number'] ?? $user->number;
        $user->email      = $validated['email'] ?? $user->email;
        $user->dob        = $validated['dob'] ?? $user->dob;
        $user->class_id   = $validated['class_id'] ?? $user->class_id;
        $user->subject_id = $validated['subject_id'] ?? $user->subject_id;
        $user->address    = $validated['address'] ?? $user->address;
        $user->city       = $validated['city'] ?? $user->city;
        $user->state      = $validated['state'] ?? $user->state;
        $user->country    = $validated['country'] ?? $user->country;
        $user->pincode    = $validated['pincode'] ?? $user->pincode;
        $user->about      = $validated['about'] ?? $user->about;

        // Update password only if provided
        if ($request->filled('password')) {
            $user->password = bcrypt($validated['password']);
        }
        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function profileBannerUpdate(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Validate the banner image
        $request->validate([
            'banner' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        // Handle banner upload
        if ($request->hasFile('banner')) {
            // Delete old banner if exists
            if ($user->banner && \Storage::disk('public')->exists($user->banner)) {
                \Storage::disk('public')->delete($user->banner);
            }

            // Store the new banner
            $path = $request->file('banner')->store('uploads/profiles/banners', 'public');

            // Save the relative path in the database
            $user->banner = 'storage/' . $path;
            $user->save();
        }

        return back()->with('success', 'Banner updated successfully.');
    }

    public function profilePictureUpdate(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Validate the profile picture
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture && \Storage::disk('public')->exists($user->profile_picture)) {
                \Storage::disk('public')->delete($user->profile_picture);
            }

            // Store the new profile picture
            $path = $request->file('profile_picture')->store('uploads/profiles/pictures', 'public');

            // Save the relative path in the database
            $user->profile_picture = 'storage/' . $path;
            $user->save();
        }

        return back()->with('success', 'Profile picture updated successfully.');
    }
}
