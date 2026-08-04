<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * TenantSyncSeeder — Safe "upsert" seeder.
 *
 * Purpose  : Run after every `tenant:migrate` to ensure new system
 *            pages and other required rows exist.
 *            Uses insertOrIgnore() so existing data is NEVER deleted
 *            or overwritten.
 *
 * Add new "required rows" here as the system grows.
 */
class TenantSyncSeeder extends Seeder
{
    /**
     * ┌─────────────────────────────────────────────────────────────┐
     * │  SYSTEM PAGES  (is_system_page = true)                      │
     * │  → Editable ONLY via Theme Builder                          │
     * │                                                             │
     * │  NON-SYSTEM PAGES  (is_system_page = false)                 │
     * │  → Editable via Admin → Website → Pages (Shopify-style)     │
     * │                                                             │
     * │  Add new rows here as the system grows.                     │
     * │  `tenant:migrate` will insert missing rows automatically.   │
     * └─────────────────────────────────────────────────────────────┘
     */
    private array $systemPages = [
        // ── SYSTEM PAGES (theme builder editable) ──────────────────
        ['name' => 'Home',           'slug' => 'home',            'is_system_page' => true],
        ['name' => 'Courses',        'slug' => 'courses',         'is_system_page' => true],
        ['name' => 'View Course',    'slug' => 'course',          'is_system_page' => true],
        ['name' => 'Blogs',          'slug' => 'blogs',           'is_system_page' => true],
        ['name' => 'View Blog',      'slug' => 'blog',            'is_system_page' => true],
        ['name' => 'Book Categories','slug' => 'book-categories', 'is_system_page' => true],
        ['name' => 'Courses Categories', 'slug' => 'categories',   'is_system_page' => true],
        ['name' => 'Books',          'slug' => 'books',           'is_system_page' => true],
        ['name' => 'View Book',      'slug' => 'book',            'is_system_page' => true],
        ['name' => 'Checkout',       'slug' => 'checkout',        'is_system_page' => true],
        ['name' => 'Checkout Success','slug' => 'checkout-success', 'is_system_page' => true],
        ['name' => 'Contact',          'slug' => 'contact',          'is_system_page' => true],
        ['name' => 'Login',            'slug' => 'login',            'is_system_page' => true],
        ['name' => 'Register',         'slug' => 'register',         'is_system_page' => true],

        // ── NON-SYSTEM PAGES (pages manager editable) ─────────────
        ['name' => 'Privacy Policy',   'slug' => 'privacy-policy',   'is_system_page' => false],
        ['name' => 'Terms & Conditions','slug' => 'terms-conditions', 'is_system_page' => false],
    ];

    public function run(): void
    {
        $this->syncPages();
        $this->syncThemeLayouts();
        $this->syncMenus();
    }

    private function syncMenus(): void
    {
        // 1. Ensure 'Header' and 'Footer' menus exist
        $headerMenuId = DB::table('menus')->where('slug', 'header')->value('id');
        if (!$headerMenuId) {
            $headerMenuId = DB::table('menus')->insertGetId([
                'name' => 'Header',
                'slug' => 'header',
                'location' => 'header',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command?->info("  ✚ Header menu created");
        }

        $footerMenuId = DB::table('menus')->where('slug', 'footer')->value('id');
        if (!$footerMenuId) {
            $footerMenuId = DB::table('menus')->insertGetId([
                'name' => 'Footer',
                'slug' => 'footer',
                'location' => 'footer',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command?->info("  ✚ Footer menu created");
        }

        // 2. Ensure basic menu items exist for Header
        $headerItems = [
            ['name' => 'Home', 'link' => '/', 'order' => 1],
            ['name' => 'Courses', 'link' => '/courses', 'order' => 2],
            ['name' => 'Books', 'link' => '/books', 'order' => 3],
            ['name' => 'Contact', 'link' => '/contact', 'order' => 4],
        ];

        foreach ($headerItems as $item) {
            $exists = DB::table('menu_items')
                ->where('menu_id', $headerMenuId)
                ->where('name', $item['name'])
                ->exists();

            if (!$exists) {
                DB::table('menu_items')->insert([
                    'menu_id' => $headerMenuId,
                    'name' => $item['name'],
                    'link' => $item['link'],
                    'order' => $item['order'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->command?->info("  ✚ Header Item added: {$item['name']}");
            }
        }

        // 3. Ensure basic menu items exist for Footer
        $footerItems = [
            ['name' => 'Privacy Policy', 'link' => '/privacy-policy', 'order' => 1],
            ['name' => 'Terms & Conditions', 'link' => '/terms-conditions', 'order' => 2],
        ];

        foreach ($footerItems as $item) {
            $exists = DB::table('menu_items')
                ->where('menu_id', $footerMenuId)
                ->where('name', $item['name'])
                ->exists();

            if (!$exists) {
                DB::table('menu_items')->insert([
                    'menu_id' => $footerMenuId,
                    'name' => $item['name'],
                    'link' => $item['link'],
                    'order' => $item['order'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->command?->info("  ✚ Footer Item added: {$item['name']}");
            }
        }
    }

    private function syncThemeLayouts(): void
    {
        $tenantTheme = \App\Models\Tenant\TenantTheme::where('is_active', true)->first();
        if ($tenantTheme && $tenantTheme->theme_slug) {
            \App\Services\Theme\ThemeInstaller::installForTenant(
                $tenantTheme->theme_slug,
                $tenantTheme
            );
            $this->command?->info("  ✔ Theme layouts synced for [{$tenantTheme->theme_slug}]");
        }
    }

    // =========================================================
    // PAGES — insertOrIgnore (slug must be unique in your schema)
    // =========================================================
    private function syncPages(): void
    {
        foreach ($this->systemPages as $page) {
            $existing = DB::table('pages')->where('slug', $page['slug'])->first();

            if (!$existing) {
                DB::table('pages')->insert([
                    'name' => $page['name'],
                    'slug' => $page['slug'],
                    'is_system_page' => $page['is_system_page'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $this->command?->info("  ✚ Page added: [{$page['slug']}] {$page['name']}");
            } else {
                if ($existing->name !== $page['name'] || $existing->is_system_page != $page['is_system_page']) {
                    DB::table('pages')->where('slug', $page['slug'])->update([
                        'name' => $page['name'],
                        'is_system_page' => $page['is_system_page'],
                        'updated_at' => now(),
                    ]);
                    $this->command?->info("  ✔ Page updated: [{$page['slug']}] -> {$page['name']} (is_system_page: {$page['is_system_page']})");
                } else {
                    $this->command?->line("  ✔ Page OK:    [{$page['slug']}] {$page['name']}");
                }
            }
        }
    }
}
