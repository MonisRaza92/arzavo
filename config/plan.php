<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Platform Features & Capabilities
    |--------------------------------------------------------------------------
    |
    | Consolidated, logical module-level features across the Arzavo SaaS platform.
    |
    */
    'features' => [
        // Academics & Learning
        'courses_upload'        => 'Courses & Curriculum Management',
        'live_classes'          => 'Live Interactive Classes',
        'quizzes_and_test'      => 'Quizzes & Practice Tests',
        'online_exams'          => 'Online Exams & Result Generation',
        'batch_managment'       => 'Batch & Schedule Management',
        'online_attendence'     => 'Attendance Management',
        'certificates'          => 'Automated Certificate Generation',

        // Digital Library
        'books_and_notes'       => 'Books & Notes Library',

        // People & Campus Management
        'student_management'    => 'Students Management',
        'teacher_management'    => 'Teachers Management',
        'staff_management'      => 'Staff Management',

        // Finance & Billing
        'online_fee_collection' => 'Online Fee Collection & Payments',

        // Website Builder & Branding
        'custom_domain'         => 'Custom Domain Mapping',
        'website_builder'       => 'Website Builder, Themes & Custom Pages',
        'blogs_and_stories'     => 'Blogs & Content Asset Management',

        // Communication & Insights
        'communication'         => 'Communication, Inquiries & Newsletters',
        'analytics'             => 'Advanced Reports & Analytics',
    ],

    /*
    |--------------------------------------------------------------------------
    | Resource Limits & Quotas
    |--------------------------------------------------------------------------
    */
    'limits' => [
        'admin'     => 'Admin Limit',
        'teachers'  => 'Teachers Limit',
        'staff'     => 'Staff Limit',
        'students'  => 'Students Limit',
        'courses'   => 'Courses Limit',
        'storage'   => 'Storage (GB)',
    ],

];