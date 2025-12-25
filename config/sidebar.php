<?php

return [

    // ======================================================
    // 1. REPORTS & ANALYTICS
    // ======================================================
    [
        'section' => 'Reports & Analytics',
        'items'   => [
            [
                'type'   => 'link',
                'icon'   => 'fa-bars-progress',
                'text'   => 'Dashboard',
                'route'  => 'admin.dashboard.index',
                'active' => 'admin/dashboard',
            ],
        ],
    ],

    // ======================================================
    // 2. ACADEMICS (SCHOOLS + COLLEGES + COACHING)
    // ======================================================
    [
        'section' => 'Academics',
        'items'   => [
            [
                'type' => 'menu',
                'id'   => 'academicsMenu',
                'icon' => 'fa-school',
                'text' => 'Academics',
                'links' => [
                    ['icon' => 'fa-book-open', 'text' => 'Courses / Subjects', 'route' => '#'],
                    ['icon' => 'fa-clipboard-list', 'text' => 'Syllabus / Curriculum', 'route' => '#'],
                    ['icon' => 'fa-calendar-days', 'text' => 'Academic Calendar', 'route' => '#'],
                    ['icon' => 'fa-file-pen', 'text' => 'Exams', 'route' => '#'],
                    ['icon' => 'fa-graduation-cap', 'text' => 'Results', 'route' => '#'],
                    ['icon' => 'fa-stopwatch', 'text' => 'Timetable', 'route' => '#'],
                    ['icon' => 'fa-certificate', 'text' => 'Certificates', 'route' => '#'],
                ],
            ],
            [
                'type' => 'menu',
                'id' => 'classesMenu',
                'icon' => 'fa-layer-group',
                'text' => 'Classes & Batches',
                'links' => [
                    ['icon' => 'fa-users', 'text' => 'Class List', 'route' => '#'],
                    ['icon' => 'fa-users-viewfinder', 'text' => 'Batch Management', 'route' => '#'],
                    ['icon' => 'fa-calendar-check', 'text' => 'Class Schedule', 'route' => '#'],
                    ['icon' => 'fa-user-check', 'text' => 'Attendance', 'route' => '#'],
                ],
            ],
            [
                'type' => 'menu',
                'id' => 'financeMenu',
                'icon' => 'fa-wallet',
                'text' => 'Finance',
                'links' => [
                    ['icon' => 'fa-coins', 'text' => 'Student Fees', 'route' => '#'],
                    ['icon' => 'fa-sack-dollar', 'text' => 'Salary (Teachers/Staff)', 'route' => '#'],
                    ['icon' => 'fa-file-invoice', 'text' => 'Invoices', 'route' => '#'],
                    ['icon' => 'fa-money-check-alt', 'text' => 'Expenses', 'route' => '#'],
                    ['icon' => 'fa-chart-line', 'text' => 'Financial Reports', 'route' => '#'],
                ],
            ],
            [
                'type' => 'menu',
                'id' => 'contentMenu',
                'icon' => 'fa-graduation-cap',
                'text' => 'Content',
                'links' => [
                    ['icon' => 'fa-video', 'text' => 'Video Lessons', 'route' => '#'],
                    ['icon' => 'fa-file-alt', 'text' => 'Study Materials', 'route' => '#'],
                    ['icon' => 'fa-clipboard-check', 'text' => 'Assignments', 'route' => '#'],
                    ['icon' => 'fa-question-circle', 'text' => 'Quizzes', 'route' => '#'],
                    ['icon' => 'fa-chalkboard', 'text' => 'Live Classes', 'route' => '#'],
                ],
            ],
        ],
    ],

    // ======================================================
    // 4. USERS MANAGEMENT
    // ======================================================
    [
        'section' => 'Users & Staff',
        'items' => [
            [
                'type' => 'link',
                'icon' => 'fa-users',
                'text' => 'Users',
                'route'  => '#',
            ],
            [
                'type' => 'menu',
                'id' => 'studentsMenu',
                'icon' => 'fa-user-graduate',
                'text' => 'Students',
                'links' => [
                    ['icon' => 'fa-list', 'text' => 'Student List', 'route' => '#'],
                    ['icon' => 'fa-user-plus', 'text' => 'Admissions', 'route' => '#'],
                    ['icon' => 'fa-user-clock', 'text' => 'Attendance', 'route' => '#'],
                    ['icon' => 'fa-chart-simple', 'text' => 'Performance Reports', 'route' => '#'],
                    ['icon' => 'fa-money-bill-wave', 'text' => 'Fees & Billing', 'route' => '#'],
                    ['icon' => 'fa-comment-dots', 'text' => 'Feedback', 'route' => '#'],
                    ['icon' => 'fa-id-card', 'text' => 'ID Card Generator', 'route' => '#'],
                ],
            ],
            [
                'type' => 'menu',
                'id' => 'teachersMenu',
                'icon' => 'fa-chalkboard-teacher',
                'text' => 'Teachers',
                'links' => [
                    ['icon' => 'fa-list', 'text' => 'Teacher List', 'route' => '#'],
                    ['icon' => 'fa-user-tie', 'text' => 'Assign Subjects', 'route' => '#'],
                    ['icon' => 'fa-user-clock', 'text' => 'Attendance', 'route' => '#'],
                    ['icon' => 'fa-money-check', 'text' => 'Salary & Payroll', 'route' => '#'],
                    ['icon' => 'fa-pie-chart', 'text' => 'Performance', 'route' => '#'],
                    ['icon' => 'fa-briefcase', 'text' => 'Teacher Portal', 'route' => '#'],
                ],
            ],
            [
                'type' => 'menu',
                'id' => 'staffMenu',
                'icon' => 'fa-users-gear',
                'text' => 'Staff Management',
                'links' => [
                    ['icon' => 'fa-users', 'text' => 'Staff List', 'route' => '#'],
                    ['icon' => 'fa-user-clock', 'text' => 'Attendance', 'route' => '#'],
                    ['icon' => 'fa-money-check-dollar', 'text' => 'Salary & Payroll', 'route' => '#'],
                    ['icon' => 'fa-id-card', 'text' => 'ID Cards', 'route' => '#'],
                ],
            ],
        ],
    ],


    // ======================================================
    // 14. TENANT SHOP (E-COMMERCE)
    // ======================================================
    [
        'section' => 'Shop (E-Commerce)',
        'items' => [
            [
                'type' => 'menu',
                'id' => 'shopMenu',
                'icon' => 'fa-store',
                'text' => 'Tenant Shop',
                'links' => [
                    ['icon' => 'fa-box', 'text' => 'Products', 'route' => '#'],
                    ['icon' => 'fa-tags', 'text' => 'Categories', 'route' => '#'],
                    ['icon' => 'fa-ticket', 'text' => 'Coupons', 'route' => '#'],
                    ['icon' => 'fa-shopping-cart', 'text' => 'Orders', 'route' => '#'],
                    ['icon' => 'fa-credit-card', 'text' => 'Payments', 'route' => '#'],
                    ['icon' => 'fa-cog', 'text' => 'Shop Settings', 'route' => '#'],
                ],
            ],
        ],
    ],

    // ======================================================
    // 15. COMMUNICATION
    // ======================================================
    [
        'section' => 'Communication',
        'items' => [
            [
                'type' => 'menu',
                'id' => 'commMenu',
                'icon' => 'fa-comments',
                'text' => 'Communication',
                'links' => [
                    ['icon' => 'fa-envelope', 'text' => 'Messages', 'route' => '#'],
                    ['icon' => 'fa-bell', 'text' => 'Notifications', 'route' => '#'],
                    ['icon' => 'fa-video', 'text' => 'Live Classes', 'route' => '#'],
                    ['icon' => 'fa-users-rectangle', 'text' => 'Forums', 'route' => '#'],
                ],
            ],
            [
                'type' => 'menu',
                'id' => 'crmMenu',
                'icon' => 'fa-headset',
                'text' => 'Enquiries',
                'links' => [
                    ['icon' => 'fa-user', 'text' => 'Leads / Enquiries', 'route' => '#'],
                    ['icon' => 'fa-address-book', 'text' => 'Followups', 'route' => '#'],
                    ['icon' => 'fa-bullhorn', 'text' => 'Campaigns', 'route' => '#'],
                    ['icon' => 'fa-phone', 'text' => 'Call Logs', 'route' => '#'],
                ],
            ],
        ],
    ],

    // ======================================================
    // 13. WEBSITE BUILDER SYSTEM
    // ======================================================
    [
        'section' => 'Website Builder',
        'items' => [
            [
                'type' => 'menu',
                'id' => 'websiteMenu',
                'icon' => 'fa-globe',
                'text' => 'Website & Theme',
                'links' => [
                    ['icon' => 'fa-pen-nib', 'text' => 'Themes', 'route' => 'admin.themes.index', 'active' => 'admin/themes'],
                    ['icon' => 'fa-window-restore', 'text' => 'Pages', 'route' => 'admin.pages.index', 'active' => 'admin/pages'],
                    ['icon' => 'fa-image', 'text' => 'Media Library', 'route' => 'admin.images.index', 'active' => 'admin/images'],
                    // ['icon' => 'fa-palette', 'text' => 'Themes', 'route' => '#'],
                    ['icon' => 'fa-cog', 'text' => 'Website Settings', 'route' => '#'],
                ],
            ],
        ],
    ],

    
    // ======================================================
    // 16. SETTINGS
    // ======================================================
    [
        'section' => 'Settings',
        'items' => [
            [
                'type' => 'menu',
                'id' => 'settingsMenu',
                'icon' => 'fa-gear',
                'text' => 'Settings',
                'links' => [
                    ['icon' => 'fa-list', 'text' => 'Modules', 'route' => '#'],
                    ['icon' => 'fa-sliders', 'text' => 'General Settings', 'route' => '#'],
                    ['icon' => 'fa-lock', 'text' => 'Roles & Permissions', 'route' => '#'],
                    ['icon' => 'fa-shield-halved', 'text' => 'Security', 'route' => '#'],
                    ['icon' => 'fa-globe', 'text' => 'Language', 'route' => '#'],
                    ['icon' => 'fa-headset', 'text' => 'Support Center', 'route' => '#'],
                ],
            ],
        ],
    ],

];
