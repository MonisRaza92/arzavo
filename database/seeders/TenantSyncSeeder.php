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
        ['name' => 'Books',          'slug' => 'books',           'is_system_page' => true],
        ['name' => 'View Book',      'slug' => 'book',            'is_system_page' => true],

        // ── NON-SYSTEM PAGES (pages manager editable) ─────────────
        ['name' => 'Contact',          'slug' => 'contact',          'is_system_page' => false],
        ['name' => 'Privacy Policy',   'slug' => 'privacy-policy',   'is_system_page' => false],
        ['name' => 'Terms & Conditions','slug' => 'terms-conditions', 'is_system_page' => false],
    ];

    public function run(): void
    {
        $this->syncPages();
        $this->syncThemeLayouts();
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
            $exists = DB::table('pages')->where('slug', $page['slug'])->exists();

            if (!$exists) {
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
                $this->command?->line("  ✔ Page OK:    [{$page['slug']}] {$page['name']}");
            }
        }
    }
}
