<?php

namespace App\Http\Controllers\Arzavo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Arzavo\Plan;

class HomeController
{
    public function index()
    {
        $plans = Plan::where('is_active', true)
            ->orderByDesc('is_popular')
            ->orderBy('monthly_price')
            ->get();
        return view('arzavo.website.home.index', compact('plans'));
    }

    public function about()
    {
        return view('arzavo.website.about.index');
    }



    public function pricing()
    {
        $plans = Plan::where('is_active', true)
            ->orderByDesc('is_popular')
            ->orderBy('monthly_price')
            ->get();
        return view('arzavo.website.pricing.index', compact('plans'));
    }

    public function features()
    {
        return view('arzavo.website.features.index');
    }

    public function contact()
    {
        return view('arzavo.website.contact.index');
    }

    public function privacy()
    {
        return view('arzavo.website.privacy.index');
    }

    public function terms()
    {
        return view('arzavo.website.terms.index');
    }

    public function refunds()
    {
        return view('arzavo.website.refunds.index');
    }

    public function cookiePolicy()
    {
        return view('arzavo.website.cookies.index');
    }

    public function dataRetention()
    {
        return view('arzavo.website.retention.index');
    }

    public function acceptableUse()
    {
        return view('arzavo.website.aup.index');
    }

    public function security()
    {
        return view('arzavo.website.security.index');
    }

    public function dataOwnership()
    {
        return view('arzavo.website.ownership.index');
    }

    public function studentPrivacy()
    {
        return view('arzavo.website.student-privacy.index');
    }

    public function communicationPolicy()
    {
        return view('arzavo.website.communication-policy.index');
    }

    public function dpa()
    {
        return view('arzavo.website.dpa.index');
    }

    public function subprocessors()
    {
        return view('arzavo.website.subprocessors.index');
    }

    public function trust()
    {
        return view('arzavo.website.trust.index');
    }

    public function legal()
    {
        return view('arzavo.website.legal.index');
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
