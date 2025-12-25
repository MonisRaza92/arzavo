<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Arzavo\Theme;
use App\Models\Tenant\ThemeState;

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

        /**
         * YAHAN actual theme apply logic call hoga
         * (JSON → sections → blocks)
         */
        // app(\App\Services\Theme\ThemeApplyService::class)->apply($theme);

        DB::table('pages')->insert([
            [
                'name' => 'Home',
                'slug' => 'home',
                'status' => true,
                'created_at'=> now(),
            ],
        ]);
        DB::table('color_schemes')->insert([
            [
                'colors' => json_encode([
                    'scheme_colors' => [
                        'background' => '#ffffff',
                        'heading' => '#111111',
                        'subheading' => '#1f1f1f',
                        'paragraph' => '#3a3a3a',
                        'secondary_text' => '#6b6b6b',
                        'link' => '#111111',
                        'link_hover' => '#000000',
                        'border' => '#e5e5e5',
                        'shadow' => 'rgba(0,0,0,0.05)',
                    ],
                    'primary_btn' => [
                        'background' => '#111111',
                        'text' => '#ffffff',
                        'hover_background' => '#000000',
                        'hover_text' => '#ffffff',
                        'border' => '#111111',
                        'hover_border' => '#000000'
                    ],
                    'secondary_btn' => [
                        'background' => 'transparent',
                        'text' => '#111111',
                        'hover_background' => 'rgba(0,0,0,0.05)',
                        'hover_text' => '#000000',
                        'border' => '#d4d4d4',
                        'hover_border' => '#000000'
                    ],
                    'input' => [
                        'background' => '#ffffff',
                        'text' => '#111111',
                        'border' => '#e5e5e5',
                        'focus_border' => '#111111'
                    ]
                ])
            ],


            [
                'colors' => json_encode([
                    'scheme_colors' => [
                        'background' => '#faf9f7',
                        'heading' => '#2c2c2c',
                        'subheading' => '#444444',
                        'paragraph' => '#555555',
                        'secondary_text' => '#7a7a7a',
                        'link' => '#7c3aed',
                        'link_hover' => '#6d28d9',
                        'border' => '#e8e6e3',
                        'shadow' => 'rgba(0,0,0,0.04)',
                    ],
                    'primary_btn' => [
                        'background' => '#7c3aed',
                        'text' => '#ffffff',
                        'hover_background' => '#6d28d9',
                        'hover_text' => '#ffffff',
                        'border' => '#7c3aed',
                        'hover_border' => '#6d28d9'
                    ],
                    'secondary_btn' => [
                        'background' => 'transparent',
                        'text' => '#7c3aed',
                        'hover_background' => 'rgba(124,58,237,0.08)',
                        'hover_text' => '#6d28d9',
                        'border' => '#d6d3d1',
                        'hover_border' => '#7c3aed'
                    ],
                    'input' => [
                        'background' => '#ffffff',
                        'text' => '#2c2c2c',
                        'border' => '#e8e6e3',
                        'focus_border' => '#7c3aed'
                    ]
                ])
            ],


            [
                // 3. Ocean Depth - Premium blue
                'colors' => json_encode([
                    'scheme_colors' => [
                        'background' => '#ffffff',
                        'heading' => '#0c4a6e',
                        'subheading' => '#075985',
                        'paragraph' => '#0369a1',
                        'secondary_text' => '#64748b',
                        'link' => '#0ea5e9',
                        'link_hover' => '#0284c7',
                        'border' => '#e0f2fe',
                        'shadow' => 'rgba(14, 165, 233, 0.1)',
                    ],
                    'primary_btn' => [
                        'background' => '#0284c7',
                        'text' => '#ffffff',
                        'hover_background' => '#0369a1',
                        'hover_text' => '#ffffff',
                        'border' => '#0284c7',
                        'hover_border' => '#0369a1'
                    ],
                    'secondary_btn' => [
                        'background' => '#f0f9ff',
                        'text' => '#0284c7',
                        'hover_background' => '#e0f2fe',
                        'hover_text' => '#0369a1',
                        'border' => '#bae6fd',
                        'hover_border' => '#7dd3fc'
                    ],
                    'input' => [
                        'background' => '#ffffff',
                        'text' => '#0c4a6e',
                        'border' => '#e0f2fe',
                        'focus_border' => '#0284c7'
                    ]
                ])
            ],

            [
                'colors' => json_encode([
                    'scheme_colors' => [
                        'background' => 'rgba(255,255,255,0.85)',
                        'heading' => '#0f172a',
                        'subheading' => '#1e293b',
                        'paragraph' => '#475569',
                        'secondary_text' => '#64748b',
                        'link' => '#2563eb',
                        'link_hover' => '#1d4ed8',
                        'border' => 'rgba(0,0,0,0.08)',
                        'shadow' => 'rgba(0,0,0,0.06)',
                    ],
                    'primary_btn' => [
                        'background' => '#2563eb',
                        'text' => '#ffffff',
                        'hover_background' => '#1d4ed8',
                        'hover_text' => '#ffffff',
                        'border' => '#2563eb',
                        'hover_border' => '#1d4ed8'
                    ],
                    'secondary_btn' => [
                        'background' => 'rgba(37,99,235,0.08)',
                        'text' => '#2563eb',
                        'hover_background' => 'rgba(37,99,235,0.15)',
                        'hover_text' => '#1d4ed8',
                        'border' => 'rgba(37,99,235,0.25)',
                        'hover_border' => 'rgba(37,99,235,0.4)'
                    ],
                    'input' => [
                        'background' => 'rgba(255,255,255,0.7)',
                        'text' => '#0f172a',
                        'border' => 'rgba(0,0,0,0.1)',
                        'focus_border' => '#2563eb'
                    ]
                ])
            ],


            [
                // 5. Warm Sand - Beige luxury
                'colors' => json_encode([
                    'scheme_colors' => [
                        'background' => '#fefdfb',
                        'heading' => '#451a03',
                        'subheading' => '#78350f',
                        'paragraph' => '#92400e',
                        'secondary_text' => '#a16207',
                        'link' => '#ca8a04',
                        'link_hover' => '#a16207',
                        'border' => '#fef3c7',
                        'shadow' => 'rgba(202, 138, 4, 0.08)',
                    ],
                    'primary_btn' => [
                        'background' => '#ca8a04',
                        'text' => '#ffffff',
                        'hover_background' => '#a16207',
                        'hover_text' => '#ffffff',
                        'border' => '#ca8a04',
                        'hover_border' => '#a16207'
                    ],
                    'secondary_btn' => [
                        'background' => '#fffbeb',
                        'text' => '#92400e',
                        'hover_background' => '#fef3c7',
                        'hover_text' => '#78350f',
                        'border' => '#fde68a',
                        'hover_border' => '#fcd34d'
                    ],
                    'input' => [
                        'background' => '#fffbeb',
                        'text' => '#451a03',
                        'border' => '#fef3c7',
                        'focus_border' => '#ca8a04'
                    ]
                ])
            ],

            [
                'colors' => json_encode([
                    'scheme_colors' => [
                        'background' => '#f7faff',
                        'heading' => '#0f172a',
                        'subheading' => '#1e293b',
                        'paragraph' => '#475569',
                        'secondary_text' => '#64748b',
                        'link' => '#3b82f6',
                        'link_hover' => '#2563eb',
                        'border' => '#e2e8f0',
                        'shadow' => 'rgba(0,0,0,0.04)',
                    ],
                    'primary_btn' => [
                        'background' => '#3b82f6',
                        'text' => '#ffffff',
                        'hover_background' => '#2563eb',
                        'hover_text' => '#ffffff',
                        'border' => '#3b82f6',
                        'hover_border' => '#2563eb'
                    ],
                    'secondary_btn' => [
                        'background' => '#ffffff',
                        'text' => '#3b82f6',
                        'hover_background' => '#f0f5ff',
                        'hover_text' => '#2563eb',
                        'border' => '#dbeafe',
                        'hover_border' => '#bfdbfe'
                    ],
                    'input' => [
                        'background' => '#ffffff',
                        'text' => '#0f172a',
                        'border' => '#e2e8f0',
                        'focus_border' => '#3b82f6'
                    ]
                ])
            ],


            [
                // 7. Rose Gold - Premium pink/gold
                'colors' => json_encode([
                    'scheme_colors' => [
                        'background' => '#fff5f7',
                        'heading' => '#881337',
                        'subheading' => '#9f1239',
                        'paragraph' => '#4c0519',
                        'secondary_text' => '#be123c',
                        'link' => '#e11d48',
                        'link_hover' => '#be123c',
                        'border' => '#ffe4e6',
                        'shadow' => 'rgba(225, 29, 72, 0.1)',
                    ],
                    'primary_btn' => [
                        'background' => '#e11d48',
                        'text' => '#ffffff',
                        'hover_background' => '#be123c',
                        'hover_text' => '#ffffff',
                        'border' => '#e11d48',
                        'hover_border' => '#be123c'
                    ],
                    'secondary_btn' => [
                        'background' => '#ffffff',
                        'text' => '#e11d48',
                        'hover_background' => '#fff1f2',
                        'hover_text' => '#be123c',
                        'border' => '#fecdd3',
                        'hover_border' => '#fda4af'
                    ],
                    'input' => [
                        'background' => '#ffffff',
                        'text' => '#881337',
                        'border' => '#ffe4e6',
                        'focus_border' => '#e11d48'
                    ]
                ])
            ],

            [
                // 8. Tech Teal - Modern cyan
                'colors' => json_encode([
                    'scheme_colors' => [
                        'background' => '#f0fdfa',
                        'heading' => '#134e4a',
                        'subheading' => '#115e59',
                        'paragraph' => '#0f766e',
                        'secondary_text' => '#14b8a6',
                        'link' => '#0d9488',
                        'link_hover' => '#0f766e',
                        'border' => '#ccfbf1',
                        'shadow' => 'rgba(13, 148, 136, 0.1)',
                    ],
                    'primary_btn' => [
                        'background' => '#0d9488',
                        'text' => '#ffffff',
                        'hover_background' => '#0f766e',
                        'hover_text' => '#ffffff',
                        'border' => '#0d9488',
                        'hover_border' => '#0f766e'
                    ],
                    'secondary_btn' => [
                        'background' => '#ffffff',
                        'text' => '#0d9488',
                        'hover_background' => '#f0fdfa',
                        'hover_text' => '#0f766e',
                        'border' => '#99f6e4',
                        'hover_border' => '#5eead4'
                    ],
                    'input' => [
                        'background' => '#ffffff',
                        'text' => '#134e4a',
                        'border' => '#ccfbf1',
                        'focus_border' => '#0d9488'
                    ]
                ])
            ],

            [
                // 1. Sunset Gradient - Warm orange to pink
                'colors' => json_encode([
                    'scheme_colors' => [
                        'background' => 'linear-gradient(135deg, #ff6b6b 0%, #feca57 50%, #ee5a6f 100%)',
                        'heading' => '#ffffff',
                        'subheading' => '#fff5f5',
                        'paragraph' => '#fef5e7',
                        'secondary_text' => '#ffe0e0',
                        'link' => '#ffffff',
                        'link_hover' => '#fff5f5',
                        'border' => 'rgba(255, 255, 255, 0.3)',
                        'shadow' => 'rgba(238, 90, 111, 0.3)',
                    ],
                    'primary_btn' => [
                        'background' => '#ffffff',
                        'text' => '#ff6b6b',
                        'hover_background' => 'rgba(255, 255, 255, 0.9)',
                        'hover_text' => '#ee5a6f',
                        'border' => '#ffffff',
                        'hover_border' => 'rgba(255, 255, 255, 0.9)'
                    ],
                    'secondary_btn' => [
                        'background' => 'rgba(255, 255, 255, 0.15)',
                        'text' => '#ffffff',
                        'hover_background' => 'rgba(255, 255, 255, 0.25)',
                        'hover_text' => '#ffffff',
                        'border' => 'rgba(255, 255, 255, 0.4)',
                        'hover_border' => 'rgba(255, 255, 255, 0.6)'
                    ],
                    'input' => [
                        'background' => 'rgba(255, 255, 255, 0.2)',
                        'text' => '#ffffff',
                        'border' => 'rgba(255, 255, 255, 0.3)',
                        'focus_border' => '#ffffff'
                    ]
                ])
            ],

            [
                // 2. Ocean Wave - Blue to teal gradient
                'colors' => json_encode([
                    'scheme_colors' => [
                        'background' => 'linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%)',
                        'heading' => '#ffffff',
                        'subheading' => '#f0f4ff',
                        'paragraph' => '#e8eeff',
                        'secondary_text' => '#dce4ff',
                        'link' => '#ffffff',
                        'link_hover' => '#f0f4ff',
                        'border' => 'rgba(255, 255, 255, 0.25)',
                        'shadow' => 'rgba(102, 126, 234, 0.4)',
                    ],
                    'primary_btn' => [
                        'background' => '#ffffff',
                        'text' => '#667eea',
                        'hover_background' => 'rgba(255, 255, 255, 0.95)',
                        'hover_text' => '#764ba2',
                        'border' => '#ffffff',
                        'hover_border' => 'rgba(255, 255, 255, 0.95)'
                    ],
                    'secondary_btn' => [
                        'background' => 'rgba(255, 255, 255, 0.1)',
                        'text' => '#ffffff',
                        'hover_background' => 'rgba(255, 255, 255, 0.2)',
                        'hover_text' => '#ffffff',
                        'border' => 'rgba(255, 255, 255, 0.35)',
                        'hover_border' => 'rgba(255, 255, 255, 0.5)'
                    ],
                    'input' => [
                        'background' => 'rgba(255, 255, 255, 0.15)',
                        'text' => '#ffffff',
                        'border' => 'rgba(255, 255, 255, 0.25)',
                        'focus_border' => '#ffffff'
                    ]
                ])
            ],

            [
                // 3. Emerald Dream - Green gradient
                'colors' => json_encode([
                    'scheme_colors' => [
                        'background' => 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)',
                        'heading' => '#ffffff',
                        'subheading' => '#f0fff4',
                        'paragraph' => '#e6ffed',
                        'secondary_text' => '#d1f5e3',
                        'link' => '#ffffff',
                        'link_hover' => '#f0fff4',
                        'border' => 'rgba(255, 255, 255, 0.3)',
                        'shadow' => 'rgba(17, 153, 142, 0.35)',
                    ],
                    'primary_btn' => [
                        'background' => '#ffffff',
                        'text' => '#11998e',
                        'hover_background' => 'rgba(255, 255, 255, 0.92)',
                        'hover_text' => '#0d7a71',
                        'border' => '#ffffff',
                        'hover_border' => 'rgba(255, 255, 255, 0.92)'
                    ],
                    'secondary_btn' => [
                        'background' => 'rgba(255, 255, 255, 0.12)',
                        'text' => '#ffffff',
                        'hover_background' => 'rgba(255, 255, 255, 0.22)',
                        'hover_text' => '#ffffff',
                        'border' => 'rgba(255, 255, 255, 0.35)',
                        'hover_border' => 'rgba(255, 255, 255, 0.55)'
                    ],
                    'input' => [
                        'background' => 'rgba(255, 255, 255, 0.18)',
                        'text' => '#ffffff',
                        'border' => 'rgba(255, 255, 255, 0.3)',
                        'focus_border' => '#ffffff'
                    ]
                ])
            ],

            [
                // 4. Midnight Aurora - Dark purple to blue
                'colors' => json_encode([
                    'scheme_colors' => [
                        'background' => 'linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%)',
                        'heading' => '#ffffff',
                        'subheading' => '#e3f2fd',
                        'paragraph' => '#bbdefb',
                        'secondary_text' => '#90caf9',
                        'link' => '#64b5f6',
                        'link_hover' => '#42a5f5',
                        'border' => 'rgba(100, 181, 246, 0.3)',
                        'shadow' => 'rgba(15, 52, 96, 0.5)',
                    ],
                    'primary_btn' => [
                        'background' => '#64b5f6',
                        'text' => '#0f3460',
                        'hover_background' => '#42a5f5',
                        'hover_text' => '#0f3460',
                        'border' => '#64b5f6',
                        'hover_border' => '#42a5f5'
                    ],
                    'secondary_btn' => [
                        'background' => 'rgba(100, 181, 246, 0.15)',
                        'text' => '#64b5f6',
                        'hover_background' => 'rgba(100, 181, 246, 0.25)',
                        'hover_text' => '#42a5f5',
                        'border' => 'rgba(100, 181, 246, 0.4)',
                        'hover_border' => 'rgba(100, 181, 246, 0.6)'
                    ],
                    'input' => [
                        'background' => 'rgba(100, 181, 246, 0.08)',
                        'text' => '#ffffff',
                        'border' => 'rgba(100, 181, 246, 0.3)',
                        'focus_border' => '#64b5f6'
                    ]
                ])
            ]
        ]);
    }
}
