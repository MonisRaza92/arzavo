<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\Courses;
use App\Models\Tenant\Page;
use App\Models\Tenant\Images;


class TenantWebsiteController extends Controller
{
    public function index($slug = 'home') 
    {
        $page = Page::where('slug', $slug)->first();
        if (! $page) {
            return view('tenant.themes.pages.coming-soon');
        }

        
        // Sirf active sections lao
        $sections = $page->sections()
        ->where('is_active', true)
        ->with('colorScheme')
        ->orderBy('order')
        ->get();
        $images = Images::all();

        return view('tenant.themes.pages.index', compact('page', 'sections'));
    }
    public function viewCourse($slug)
    {
        $course = Courses::where('slug', $slug)->first();
        $images = Images::all();
        if (!$course) {
            return back();
        }
        return view('tenants.view_course', compact('course'));
    }
}
