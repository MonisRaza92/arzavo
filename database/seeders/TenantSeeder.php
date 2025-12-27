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
                'status' => true,
                'created_at'=> now(),
            ],
        ]);

        $controller = app(ThemeController::class);
        $controller->applyThemeInternal($theme);
    }
}
