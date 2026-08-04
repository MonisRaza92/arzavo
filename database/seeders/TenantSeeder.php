<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Arzavo\Theme;
use App\Models\Tenant\TenantTheme;
use App\Services\Theme\ThemeInstaller;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ---------------------------
        // 1️⃣ Get theme from central DB
        // ---------------------------
        $theme = Theme::where('slug', 'nucleus')->firstOrFail();

        // ---------------------------
        // 2️⃣ Attach theme to tenant
        // ---------------------------
        $tenantTheme = TenantTheme::create([
            'theme_id' => $theme->id,
            'theme_slug' => $theme->slug,
            'theme_version' => $theme->version,
            'status' => 'published',
            'is_active' => true,
            'installed_at' => now(),
            'published_at' => now(),
        ]);

        // ---------------------------
        // 3️⃣ Create system pages
        // ---------------------------
        DB::table('pages')->insert([
            // ── System pages (Theme Builder editable) ─────────────────
            ['name' => 'Home',           'slug' => 'home',            'is_system_page' => true,  'is_active' => true, 'created_at' => now()],
            ['name' => 'Courses',        'slug' => 'courses',         'is_system_page' => true,  'is_active' => true, 'created_at' => now()],
            ['name' => 'View Course',    'slug' => 'course',          'is_system_page' => true,  'is_active' => true, 'created_at' => now()],
            ['name' => 'Blogs',          'slug' => 'blogs',           'is_system_page' => true,  'is_active' => true, 'created_at' => now()],
            ['name' => 'View Blog',      'slug' => 'blog',            'is_system_page' => true,  'is_active' => true, 'created_at' => now()],
            ['name' => 'Book Categories','slug' => 'book-categories', 'is_system_page' => true,  'is_active' => true, 'created_at' => now()],
            ['name' => 'Courses Categories', 'slug' => 'categories',   'is_system_page' => true,  'is_active' => true, 'created_at' => now()],
            ['name' => 'Books',          'slug' => 'books',           'is_system_page' => true,  'is_active' => true, 'created_at' => now()],
            ['name' => 'View Book',      'slug' => 'book',            'is_system_page' => true,  'is_active' => true, 'created_at' => now()],
            ['name' => 'Contact',        'slug' => 'contact',         'is_system_page' => true,  'is_active' => true, 'created_at' => now()],
            ['name' => 'Login',          'slug' => 'login',           'is_system_page' => true,  'is_active' => true, 'created_at' => now()],
            ['name' => 'Register',       'slug' => 'register',        'is_system_page' => true,  'is_active' => true, 'created_at' => now()],

            // ── Non-system pages (Pages Manager editable) ─────────────
            ['name' => 'Privacy Policy',    'slug' => 'privacy-policy',   'is_system_page' => false, 'is_active' => true, 'created_at' => now()],
            ['name' => 'Terms & Conditions','slug' => 'terms-conditions',  'is_system_page' => false, 'is_active' => true, 'created_at' => now()],
        ]);


        // ---------------------------
        // 4️⃣ Apply theme (🔥 MAIN STEP)
        // ---------------------------
        ThemeInstaller::installForTenant(
            $theme->slug,
            $tenantTheme
        );

        // ---------------------------
        // 5️⃣ Menus etc.
        // ---------------------------
        DB::table('menus')->insert([
            ['name' => 'Header', 'slug' => 'header', 'location' => 'header'],
            ['name' => 'Footer', 'slug' => 'footer', 'location' => 'footer'],
        ]);

        DB::table('menu_items')->insert([
            // Header
            ['menu_id' => 1, 'name' => 'Home', 'link' => '/', 'order' => 0],
            ['menu_id' => 1, 'name' => 'Courses', 'link' => '/courses', 'order' => 1],
            ['menu_id' => 1, 'name' => 'Books', 'link' => '/books', 'order' => 1],
            ['menu_id' => 1, 'name' => 'Contact', 'link' => '/contact', 'order' => 2],
            
            // Footer
            ['menu_id' => 2, 'name' => 'Privacy Policy', 'link' => '/privacy-policy', 'order' => 0],
            ['menu_id' => 2, 'name' => 'Terms & Conditions', 'link' => '/terms-conditions', 'order' => 1],
        ]);
    }
}
