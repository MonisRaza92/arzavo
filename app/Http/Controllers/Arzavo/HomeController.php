<?php

namespace App\Http\Controllers\Arzavo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController
{
    public function index()
    {
        return view('arzavo.home.index');
    }

    public function about()
    {
        return view('arzavo.about.index');
    }

    public function documentation()
    {
        return view('arzavo.documentation.index');
    }

    public function pricing()
    {
        return view('arzavo.pricing.index');
    }

    public function features()
    {
        return view('arzavo.features.index');
    }

    public function contact()
    {
        return view('arzavo.contact.index');
    }

    public function dashboard()
    {
        $tenants = Auth::user()->tenants ?? collect();
        return view('arzavo.dashboard.index', compact('tenants'));
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'institution' => 'nullable|string|max:255',
            'students_count' => 'nullable|string',
            'message' => 'required|string|max:1000',
        ]);

        // Here you can add logic to save contact form data or send email
        // For now, we'll just redirect back with success message
        
        return redirect()->back()->with('success', 'Thank you for your message! We will get back to you within 24 hours.');
    }
}
