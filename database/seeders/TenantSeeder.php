<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Arzavo\Theme;
use App\Models\Tenant\ThemeState;
use App\Http\Controllers\Tenant\Admin\ThemeController;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * SAFETY CHECK
         * Agar pehle se theme applied hai, dobara mat lagao
         */
        if (ThemeState::count() > 0) {
            return;
        }

        /**
         * MAIN DB se Nucleus theme lao
         */
        $theme = Theme::where('slug', 'nucleus')->first();

        if (! $theme) {
            logger()->error('Default theme "Nucleus" not found.');
            return;
        }

        /**
         * Theme state set karo (TENANT DB)
         */
        ThemeState::create([
            'theme_id' => $theme->id,
            'theme_name' => $theme->name,
            'theme_slug' => $theme->slug,
            'theme_version' => $theme->version,
            'applied_with_reset' => true,
            'applied_at' => now(),
        ]);
        // Apply theme


        DB::table('pages')->insert([
            [
                'name' => 'Home',
                'slug' => 'home',
                'is_system_page' => true,
                'is_active' => true,
                'created_at' => now(),
            ],
            [
                'name' => 'About',
                'slug' => 'about',
                'is_system_page' => true,
                'is_active' => true,
                'created_at' => now(),
            ],
            [
                'name' => 'Courses',
                'slug' => 'courses',
                'is_system_page' => true,
                'is_active' => true,
                'created_at' => now(),
            ],
            [
                'name' => 'View Course',
                'slug' => 'view-course',
                'is_system_page' => true,
                'is_active' => true,
                'created_at' => now(),
            ],
        ]);

        DB::table('menus')->insert([
            [
                'name'=> 'Header',
                'slug' => 'header',
                'location' => 'header',
            ],
        ]);
        DB::table('menu_items')->insert([
            [
                'menu_id'=> 1,
                'name'=> 'Home',
                'link' => '',
                'order' => 0,
            ],
            [
                'menu_id'=> 1,
                'name'=> 'About',
                'link' => 'about',
                'order' => 1,
            ],
            [
                'menu_id'=> 1,
                'name'=> 'Courses',
                'link' => 'courses',
                'order' => 2,
            ],
        ]);


        $controller = app(ThemeController::class);
        $controller->applyThemeInternal($theme);
    }
}
