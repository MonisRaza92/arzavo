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
            ['name' => 'Home', 'slug' => 'home', 'is_system_page' => true, 'is_active' => true, 'created_at' => now()],
            ['name' => 'About', 'slug' => 'about', 'is_system_page' => true, 'is_active' => true, 'created_at' => now()],
            ['name' => 'Courses', 'slug' => 'courses', 'is_system_page' => true, 'is_active' => true, 'created_at' => now()],
            ['name' => 'View Course', 'slug' => 'view-course', 'is_system_page' => true, 'is_active' => true, 'created_at' => now()],
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
        ]);

        DB::table('menu_items')->insert([
            ['menu_id' => 1, 'name' => 'Home', 'link' => '/', 'order' => 0],
            ['menu_id' => 1, 'name' => 'About', 'link' => 'about', 'order' => 1],
            ['menu_id' => 1, 'name' => 'Courses', 'link' => 'courses', 'order' => 2],
        ]);
    }
}
