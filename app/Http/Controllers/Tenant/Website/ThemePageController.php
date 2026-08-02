<?php

namespace App\Http\Controllers\Tenant\Website;

use Illuminate\Routing\Controller;
use App\Models\Tenant\Page;
use App\Models\Tenant\ThemePageDesign;

class ThemePageController extends Controller
{
    /**
     * SYSTEM PAGES
     */
    public function system(string $slug, string|null $view = null)
    {
        $state = $this->resolveState();

        if ($state !== 'active') {
            return $this->handleState($state);
        }

        $page = Page::where('slug', $slug)->first();

        if (!$page) {
            return $this->comingSoon();
        }

        return $this->render($page, $view);
    }

    /**
     * DYNAMIC USER PAGES
     */
    public function page(string $slug)
    {
        $state = $this->resolveState();

        if ($state !== 'active') {
            return $this->handleState($state);
        }

        $page = Page::where('slug', $slug)->firstOrFail();

        if ($page->is_system_page) {
            abort(404);
        }

        return $this->render($page);
    }

    /**
     * PREVIEW (always bypass subscription)
     */
    public function preview($theme = 'nucleus', $themeId = null, $slug = 'home')
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        app()->instance('currentThemeId', $themeId);
        app()->instance('currentThemeSlug', $theme);
        app()->instance('builderThemeId', $themeId);

        if ($slug === 'checkout') {
            $item = \App\Models\Tenant\Book::latest()->first();
            $variant = $item ? $item->getDefaultVariant() : null;
            $purchasableType = 'book';
            $purchasableId = $item ? $item->id : null;
            return view("tenant.themes.checkout", compact('item', 'variant', 'purchasableType', 'purchasableId', 'page'));
        }

        if ($slug === 'checkout-success') {
            $order = \App\Models\Tenant\Order::latest()->first() ?? new \App\Models\Tenant\Order([
                'order_number' => 'ORD-MOCK-1234',
                'payment_status' => 'paid',
                'grand_total' => 499.00,
                'payment_gateway' => 'manual_bank'
            ]);
            return view("tenant.themes.success", compact('order', 'page'));
        }

        $design = ThemePageDesign::where('tenant_theme_id', $themeId)
            ->where('page_id', $page->id)
            ->first();

        $layout = $design?->layout ?? ['sections' => []];

        return view('tenant.themes.render', compact('page', 'layout', 'theme', 'themeId'));
    }

    /**
     * CENTRAL STATE RESOLVER
     */
    private function resolveState(): string
    {
        if (request()->routeIs('website.preview')) {
            return 'active';
        }

        $tenant = app('currentTenant');
        $subscription = $tenant?->subscription;

        // ✅ If user has active subscription → ignore trial completely
        if ($subscription && $subscription->isActive()) {
            return 'active';
        }

        // ✅ No active subscription → check trial (tenant level)
        if ($tenant && $tenant->isTrialActive()) {
            return 'trial';
        }

        return 'expired';
    }

    /**
     * HANDLE STATES
     */
    private function handleState(string $state)
    {
        return match ($state) {
            'trial' => $this->comingSoon(),
            'expired' => $this->expired(),
            default => abort(403),
        };
    }

    /**
     * RENDER PAGE
     */
    private function render(Page $page, ?string $view = null)
    {
        $layout = $this->getLayoutForPage($page);

        $theme = app('currentThemeSlug');
        $themeId = app('currentThemeId');

        return view($view ?? 'tenant.themes.render', compact('page', 'layout', 'theme', 'themeId'));
    }

    /**
     * COMING SOON (TRIAL)
     */
    public function comingSoon()
    {
        return response()->view('coming-soon', [
            'tenant' => app('currentTenant')
        ], 200);
    }

    /**
     * EXPIRED
     */
    public function expired()
    {
        return response()->view('subscription-expired', [
            'tenant' => app('currentTenant')
        ], 403);
    }

    /**
     * GET PAGE LAYOUT
     */
    private function getLayoutForPage(Page $page): array
    {
        $tenantThemeId = app('currentThemeId');

        if (!$tenantThemeId) {
            return ['sections' => []];
        }

        $design = ThemePageDesign::where('tenant_theme_id', $tenantThemeId)
            ->where('page_id', $page->id)
            ->first();

        return $design?->layout ?? ['sections' => []];
    }

    /**
     * SUBMIT CONTACT FORM
     */
    public function contactSubmit(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        \App\Models\Tenant\Inquiry::create($request->only(['name', 'email', 'subject', 'message']));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for your enquiry! We will contact you soon.'
            ]);
        }

        return back()->with('success', 'Thank you for your enquiry! We will contact you soon.');
    }

    /**
     * SUBMIT NEWSLETTER FORM
     */
    public function newsletterSubmit(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $exists = \App\Models\Tenant\NewsletterSubscriber::where('email', $request->email)->exists();
        if (!$exists) {
            \App\Models\Tenant\NewsletterSubscriber::create(['email' => $request->email]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for subscribing to our newsletter!'
            ]);
        }

        return back()->with('success', 'Thank you for subscribing to our newsletter!');
    }
}