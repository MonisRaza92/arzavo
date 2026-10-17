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
    // 2. ACADEMICS & CURRICULUM
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
                    ['icon' => 'fa-graduation-cap', 'text' => 'Curriculum', 'route' => 'admin.curriculum.board', 'active' => 'admin/curriculum*'],
                    ['icon' => 'fa-users-rectangle', 'text' => 'Batches & Sections', 'route' => 'admin.batches.index', 'active' => 'admin/batches*'],
                    ['icon' => 'fa-calendar-days', 'text' => 'Holiday Calendar', 'route' => 'admin.holidays.index', 'active' => 'admin/holidays*'],
                    ['icon' => 'fa-clipboard-check', 'text' => 'Assignments', 'route' => '#'],
                    ['icon' => 'fa-chalkboard', 'text' => 'Live Classes', 'route' => '#'],
                    ['icon' => 'fa-question-circle', 'text' => 'Quizzes & Tests', 'route' => '#'],
                    ['icon' => 'fa-file-pen', 'text' => 'Exams', 'route' => '#'],
                    ['icon' => 'fa-graduation-cap', 'text' => 'Results', 'route' => '#'],
                    ['icon' => 'fa-stopwatch', 'text' => 'Timetable', 'route' => '#'],
                    ['icon' => 'fa-certificate', 'text' => 'Certificates', 'route' => '#'],
                ],
            ],
            [
                'type' => 'menu',
                'id' => 'financeMenu',
                'icon' => 'fa-wallet',
                'text' => 'Finance & Sales',
                'links' => [
                    ['icon' => 'fa-shopping-cart', 'text' => 'Orders & Sales', 'route' => 'admin.finance.orders', 'active' => 'admin/finance/orders*'],
                    ['icon' => 'fa-money-bill-wave', 'text' => 'Fee Payments', 'route' => 'admin.finance.fees', 'active' => 'admin/finance/fees*'],
                    ['icon' => 'fa-file-invoice', 'text' => 'Invoices', 'route' => 'admin.finance.invoices', 'active' => 'admin/finance/invoices*'],
                    ['icon' => 'fa-chart-line', 'text' => 'Financial Reports', 'route' => 'admin.finance.reports', 'active' => 'admin/finance/reports*'],
                ],
            ],
            [
                'type' => 'link',
                'id' => 'contentMenu',
                'icon' => 'fa-photo-film',
                'text' => 'Contents',
                'route' => 'admin.contents.index',
                'active' => 'admin/contents*',
            ],
            [
                'type' => 'link',
                'id' => 'blogsStories',
                'icon' => 'fa-blog',
                'text' => 'Blogs & Stories',
                'route' => 'admin.blog.index',
                'active' => 'admin/blog*',
            ],
        ],
    ],

    // ======================================================
    // 3. LIBRARY SYSTEM
    // ======================================================
    [
        'section' => 'Library',
        'items' => [
            [
                'type' => 'menu',
                'id'   => 'libraryMenu',
                'icon' => 'fa-book-bookmark',
                'text' => 'Library',
                'links' => [
                    ['icon' => 'fa-layer-group', 'text' => 'Books Categories', 'route' => 'admin.book-categories.index', 'active' => 'admin/book-categories*'],
                    ['icon' => 'fa-book', 'text' => 'Books & Notes', 'route' => 'admin.books.index', 'active' => 'admin/books*'],
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
                'route'  => 'admin.users',
                'active' => 'admin/users*',
            ],
            [
                'type' => 'menu',
                'id' => 'studentsMenu',
                'icon' => 'fa-user-graduate',
                'text' => 'Students',
                'links' => [
                    ['icon' => 'fa-list', 'text' => 'Student List', 'route' => 'admin.students', 'active' => 'admin/students'],
                    ['icon' => 'fa-user-plus', 'text' => 'Admissions', 'route' => 'admin.students.admissions', 'active' => 'admin/students/admissions*'],
                    ['icon' => 'fa-user-clock', 'text' => 'Attendance', 'route' => 'admin.students.attendance', 'active' => 'admin/students/attendance*'],
                    ['icon' => 'fa-money-bill-wave', 'text' => 'Fees & Billing', 'route' => 'admin.students.fees', 'active' => 'admin/students/fees*'],
                    // ['icon' => 'fa-id-card', 'text' => 'ID Card Generator', 'route' => 'admin.students.id-card', 'active' => 'admin/students/id-card*'],
                ],
            ],
            [
                'type' => 'menu',
                'id' => 'teachersMenu',
                'icon' => 'fa-chalkboard-teacher',
                'text' => 'Teachers',
                'links' => [
                    ['icon' => 'fa-list', 'text' => 'Teacher List', 'route' => 'admin.teachers', 'active' => 'admin/teachers'],
                    ['icon' => 'fa-diagram-project', 'text' => 'Classes & Subjects', 'route' => 'admin.teachers.allocations', 'active' => 'admin/teachers/allocations*'],
                    ['icon' => 'fa-user-clock', 'text' => 'Attendance', 'route' => 'admin.teachers.attendance', 'active' => 'admin/teachers/attendance*'],
                    ['icon' => 'fa-money-check-dollar', 'text' => 'Salary & Payroll', 'route' => 'admin.teachers.payroll', 'active' => 'admin/teachers/payroll*'],
                ],
            ],
            // [
            //     'type' => 'menu',
            //     'id' => 'staffMenu',
            //     'icon' => 'fa-users-gear',
            //     'text' => 'Staff Management',
            //     'links' => [
            //         ['icon' => 'fa-users', 'text' => 'Staff List', 'route' => '#'],
            //         ['icon' => 'fa-user-clock', 'text' => 'Attendance', 'route' => '#'],
            //         ['icon' => 'fa-money-check-dollar', 'text' => 'Salary & Payroll', 'route' => '#'],
            //         ['icon' => 'fa-id-card', 'text' => 'ID Cards', 'route' => '#'],
            //     ],
            // ],
        ],
    ],


    // ======================================================
    // 14. TENANT SHOP (E-COMMERCE)
    // ======================================================
    // [
    //     'section' => 'Shop (E-Commerce)',
    //     'items' => [
    //         [
    //             'type' => 'menu',
    //             'id' => 'shopMenu',
    //             'icon' => 'fa-store',
    //             'text' => 'Tenant Shop',
    //             'links' => [
    //                 ['icon' => 'fa-box', 'text' => 'Products', 'route' => '#'],
    //                 ['icon' => 'fa-tags', 'text' => 'Categories', 'route' => '#'],
    //                 ['icon' => 'fa-ticket', 'text' => 'Coupons', 'route' => '#'],
    //                 ['icon' => 'fa-shopping-cart', 'text' => 'Orders', 'route' => '#'],
    //                 ['icon' => 'fa-credit-card', 'text' => 'Payments', 'route' => '#'],
    //                 ['icon' => 'fa-cog', 'text' => 'Shop Settings', 'route' => '#'],
    //             ],
    //         ],
    //     ],
    // ],

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
                    ['icon' => 'fa-envelope-open-text', 'text' => 'Inquiries', 'route' => 'admin.communication.inquiries', 'active' => 'admin/communication/inquiries*'],
                    ['icon' => 'fa-paper-plane', 'text' => 'Newsletter', 'route' => 'admin.communication.subscribers', 'active' => 'admin/communication/subscribers*'],
                    ['icon' => 'fa-envelope', 'text' => 'Messages', 'route' => '#'],
                    ['icon' => 'fa-bell', 'text' => 'Notifications', 'route' => '#'],
                    ['icon' => 'fa-video', 'text' => 'Live Classes', 'route' => '#'],
                    ['icon' => 'fa-users-rectangle', 'text' => 'Forums', 'route' => '#'],
                ],
            ],
            // [
            //     'type' => 'menu',
            //     'id' => 'crmMenu',
            //     'icon' => 'fa-headset',
            //     'text' => 'Enquiries',
            //     'links' => [
            //         ['icon' => 'fa-user', 'text' => 'Leads / Enquiries', 'route' => '#'],
            //         ['icon' => 'fa-address-book', 'text' => 'Followups', 'route' => '#'],
            //         ['icon' => 'fa-bullhorn', 'text' => 'Campaigns', 'route' => '#'],
            //         ['icon' => 'fa-phone', 'text' => 'Call Logs', 'route' => '#'],
            //     ],
            // ],
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
                    ['icon' => 'fa-pen-nib', 'text' => 'Themes', 'route' => 'admin.themes.index', 'active' => 'admin/themes*'],
                    ['icon' => 'fa-link', 'text' => 'Menu', 'route' => 'admin.menus.index', 'active' => 'admin/menus*'],
                    ['icon' => 'fa-window-restore', 'text' => 'Pages', 'route' => 'admin.pages.index', 'active' => 'admin/pages*'],
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
                    ['icon' => 'fa-sliders', 'text' => 'General Settings', 'route' => 'admin.settings.general', 'active' => 'admin/settings/general*'],
                    ['icon' => 'fa-credit-card', 'text' => 'Payment Settings', 'route' => 'admin.settings.payments', 'active' => 'admin/settings/payments*'],
                    ['icon' => 'fa-desktop', 'text' => 'Platform Controls', 'route' => 'admin.settings.website', 'active' => 'admin/settings/website*'],
                    ['icon' => 'fa-graduation-cap', 'text' => 'Course & Academics', 'route' => 'admin.settings.academics', 'active' => 'admin/settings/academics*'],
                    ['icon' => 'fa-envelope', 'text' => 'Email & Notifications', 'route' => 'admin.settings.communication', 'active' => 'admin/settings/communication*'],
                    ['icon' => 'fa-shield-halved', 'text' => 'Security Settings', 'route' => 'admin.settings.security', 'active' => 'admin/settings/security*'],
                    ['icon' => 'fa-lock', 'text' => 'Roles & Permissions', 'route' => '#'],
                    ['icon' => 'fa-globe', 'text' => 'Language', 'route' => '#'],
                    ['icon' => 'fa-headset', 'text' => 'Support Center', 'route' => '#'],
                ],
            ],
        ],
    ],

];
