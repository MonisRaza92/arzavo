<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Routing\Controller;
use App\Models\Tenant\Page;

class TenantWebsiteController extends Controller
{
    /**
     * Render system pages (home, courses, view-course)
     */
    private function renderSystemPage(string $slug, string $view)
    {
        $page = Page::where('slug', $slug)->first();

        if (! $page) {
            return view('tenant.themes.pages.coming-soon');
        }

        $sections = $page->sections()
            ->where('is_active', true)
            ->with('colorScheme')
            ->orderBy('order')
            ->get();

        return view($view, compact('page', 'sections'));
    }

    public function home()
    {
        return $this->renderSystemPage(
            'home',
            'tenant.themes.pages.home'
        );
    }

    public function courses()
    {
        return $this->renderSystemPage(
            'courses',
            'tenant.themes.pages.courses'
        );
    }

    public function viewCourse()
    {
        return $this->renderSystemPage(
            'view/course',
            'tenant.themes.pages.view-course'
        );
    }

    /**
     * Render dynamic pages
     */
    public function pages($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        if ($page->is_system_page) {
            abort(404);
        }

        $sections = $page->sections()
            ->where('is_active', true)
            ->with('colorScheme')
            ->orderBy('order')
            ->get();

        return view('tenant.themes.pages.pages', compact('page', 'sections'));
    }
    public function preview($slug = 'home')
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        $sections = $page->sections()
            ->where('is_active', true)
            ->with('colorScheme')
            ->orderBy('order')
            ->get();

        return view('tenant.themes.pages.pages', compact('page', 'sections'));
    }
}
