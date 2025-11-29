<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // DB::table('pages')->insert([
        //     [
        //         'title' => 'Home',
        //         'slug' => 'home',
        //         'status' => true,
        //     ],
        //     [
        //         'title' => 'About Us',
        //         'slug' => 'about-us',
        //         'status' => true,
        //     ],
        //     [
        //         'title' => 'Contact Us',
        //         'slug' => 'contact-us',
        //         'status' => true,
        //     ],
        //     [
        //         'title' => 'Courses',
        //         'slug' => 'courses',
        //         'status' => true,
        //     ],
        //     [
        //         'title' => 'Blog',
        //         'slug' => 'blog',
        //         'status' => true,
        //     ],
        // ]);
        DB::table('color_schemes')->insert([
            [
                // 1. Midnight Professional - Dark elegant
                'colors' => json_encode([
                    'scheme_colors' => [
                        'background' => '#0a0e27',
                        'heading' => '#ffffff',
                        'subheading' => '#e0e7ff',
                        'paragraph' => '#c7d2fe',
                        'secondary_text' => '#a5b4fc',
                        'link' => '#818cf8',
                        'link_hover' => '#6366f1',
                        'border' => '#312e81',
                        'shadow' => 'rgba(99, 102, 241, 0.2)',
                    ],
                    'primary_btn' => [
                        'background' => '#6366f1',
                        'text' => '#ffffff',
                        'hover_background' => '#4f46e5',
                        'hover_text' => '#ffffff',
                        'border' => '#6366f1',
                        'hover_border' => '#4f46e5'
                    ],
                    'secondary_btn' => [
                        'background' => 'transparent',
                        'text' => '#818cf8',
                        'hover_background' => 'rgba(99, 102, 241, 0.1)',
                        'hover_text' => '#6366f1',
                        'border' => '#4c1d95',
                        'hover_border' => '#6366f1'
                    ],
                    'input' => [
                        'background' => '#1e1b4b',
                        'text' => '#ffffff',
                        'border' => '#312e81',
                        'focus_border' => '#6366f1'
                    ]
                ])
            ],

            [
                // 2. Minimal Sage - Soft green minimalism
                'colors' => json_encode([
                    'scheme_colors' => [
                        'background' => '#fafaf9',
                        'heading' => '#1c1917',
                        'subheading' => '#44403c',
                        'paragraph' => '#57534e',
                        'secondary_text' => '#78716c',
                        'link' => '#16a34a',
                        'link_hover' => '#15803d',
                        'border' => '#e7e5e4',
                        'shadow' => 'rgba(0, 0, 0, 0.04)',
                    ],
                    'primary_btn' => [
                        'background' => '#16a34a',
                        'text' => '#ffffff',
                        'hover_background' => '#15803d',
                        'hover_text' => '#ffffff',
                        'border' => '#16a34a',
                        'hover_border' => '#15803d'
                    ],
                    'secondary_btn' => [
                        'background' => '#ffffff',
                        'text' => '#16a34a',
                        'hover_background' => '#f5f5f4',
                        'hover_text' => '#15803d',
                        'border' => '#d6d3d1',
                        'hover_border' => '#a8a29e'
                    ],
                    'input' => [
                        'background' => '#ffffff',
                        'text' => '#1c1917',
                        'border' => '#e7e5e4',
                        'focus_border' => '#16a34a'
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
                // 4. Monochrome Elite - Pure black & white
                'colors' => json_encode([
                    'scheme_colors' => [
                        'background' => '#ffffff',
                        'heading' => '#000000',
                        'subheading' => '#171717',
                        'paragraph' => '#404040',
                        'secondary_text' => '#737373',
                        'link' => '#000000',
                        'link_hover' => '#404040',
                        'border' => '#e5e5e5',
                        'shadow' => 'rgba(0, 0, 0, 0.08)',
                    ],
                    'primary_btn' => [
                        'background' => '#000000',
                        'text' => '#ffffff',
                        'hover_background' => '#262626',
                        'hover_text' => '#ffffff',
                        'border' => '#000000',
                        'hover_border' => '#262626'
                    ],
                    'secondary_btn' => [
                        'background' => '#ffffff',
                        'text' => '#000000',
                        'hover_background' => '#fafafa',
                        'hover_text' => '#000000',
                        'border' => '#e5e5e5',
                        'hover_border' => '#a3a3a3'
                    ],
                    'input' => [
                        'background' => '#ffffff',
                        'text' => '#000000',
                        'border' => '#e5e5e5',
                        'focus_border' => '#000000'
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
                // 6. Slate Modern - Cool gray
                'colors' => json_encode([
                    'scheme_colors' => [
                        'background' => '#f8fafc',
                        'heading' => '#0f172a',
                        'subheading' => '#1e293b',
                        'paragraph' => '#334155',
                        'secondary_text' => '#64748b',
                        'link' => '#475569',
                        'link_hover' => '#334155',
                        'border' => '#e2e8f0',
                        'shadow' => 'rgba(15, 23, 42, 0.06)',
                    ],
                    'primary_btn' => [
                        'background' => '#0f172a',
                        'text' => '#ffffff',
                        'hover_background' => '#1e293b',
                        'hover_text' => '#ffffff',
                        'border' => '#0f172a',
                        'hover_border' => '#1e293b'
                    ],
                    'secondary_btn' => [
                        'background' => '#ffffff',
                        'text' => '#0f172a',
                        'hover_background' => '#f1f5f9',
                        'hover_text' => '#0f172a',
                        'border' => '#cbd5e1',
                        'hover_border' => '#94a3b8'
                    ],
                    'input' => [
                        'background' => '#ffffff',
                        'text' => '#0f172a',
                        'border' => '#e2e8f0',
                        'focus_border' => '#475569'
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
