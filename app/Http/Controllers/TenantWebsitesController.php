<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Courses;
use App\Models\Categories;
use App\Models\Page;
use App\Models\Images;


class TenantWebsitesController extends Controller
{
    public function index($slug = 'home') 
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        
        // Sirf active sections lao
        $sections = $page->sections()
        ->where('is_active', true)
        ->orderBy('order')
        ->get();
        $categories = Categories::all();
        $images = Images::all();

        return view('tenants.index', compact('page', 'sections', 'categories'));
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
