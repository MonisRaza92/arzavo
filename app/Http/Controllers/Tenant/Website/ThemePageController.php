<?php

namespace App\Http\Controllers\Tenant\Website;

use Illuminate\Routing\Controller;
use App\Models\Tenant\Page;
use App\Models\Tenant\ThemePageDesign;
use App\Models\Tenant\TenantTheme;
use App\Models\Arzavo\Theme;

class ThemePageController extends Controller
{
    /**
     * SYSTEM PAGES (home, about, courses, etc.)
     */
    public function system(string $slug, string|null $view = null)
    {
        $page = Page::where('slug', $slug)->first();

        if (! $page) {
            return view('tenant.themes.pages.coming-soon');
        }

        $layout = $this->getLayoutForPage($page);

        $theme = app('currentThemeSlug');
        $themeId = app('currentThemeId');

        return view($view ?? 'tenant.themes.render', compact('page', 'layout', 'theme' ,'themeId'));
    }

    /**
     * DYNAMIC USER PAGES
     */
    public function page(string $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        if ($page->is_system_page) {
            abort(404);
        }

        $layout = $this->getLayoutForPage($page);

        $theme = app('currentThemeSlug');
        $themeId = app('currentThemeId');

        return view('tenant.themes.render', compact('page', 'layout', 'theme', 'themeId'));
    }

    /**
     * PREVIEW (draft theme)
     */
    public function preview($theme = 'nucleus', $themeId = null, $slug = 'home')
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        app()->instance('currentThemeId', $themeId);
        app()->instance('currentThemeSlug', $theme);
        app()->instance('builderThemeId', $themeId);
        // preview theme id middleware / session se aata hai
        $design = ThemePageDesign::where('tenant_theme_id', $themeId)
            ->where('page_id', $page->id)
            ->first();
        $layout = $design?->layout ?? ['sections' => []];
        
        return view('tenant.themes.render', compact('page', 'layout', 'theme', 'themeId'));
    }

    /**
     * CORE LOGIC — THIS IS PART 5
     */
    private function getLayoutForPage(Page $page, bool $preview = false): array
    {
        // middleware ne set kiya hua
        $tenantThemeId = app('currentThemeId');

        if (! $tenantThemeId) {
            return ['sections' => []];
        }

        $design = ThemePageDesign::where('tenant_theme_id', $tenantThemeId)
            ->where('page_id', $page->id)
            ->first();

        return $design?->layout ?? ['sections' => []];
    }
}
