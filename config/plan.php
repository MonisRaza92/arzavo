<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Platform Features & Capabilities
    |--------------------------------------------------------------------------
    |
    | Comprehensive list of all modules, features, and capabilities across
    | the Arzavo SaaS platform for plan assignment and feature toggling.
    |
    */
    'features' => [
        // Academics & Curriculum
        'courses_upload'        => 'Courses & Lesson Management',
        'live_classes'          => 'Live Interactive Classes',
        'quizzes_and_test'      => 'Quizzes & Practice Tests',
        'online_exams'          => 'Online Examination & Assessment',
        'batch_managment'       => 'Batch & Section Management',
        'online_attendence'     => 'Student & Staff Attendance',
        'assignments'           => 'Assignments & Homework System',
        'certificates'          => 'Automated Certificate Generation',
        'results_and_reports'   => 'Student Results & Gradecards',

        // Library System
        'library_system'        => 'Digital Library & E-Books',
        'book_categories'       => 'Books & Notes Organization',

        // Users & Campus Management
        'student_management'    => 'Student Directory & Profiles',
        'student_admissions'    => 'Online Admissions & Enrollment',
        'id_card_generator'     => 'Student & Staff ID Card Generator',
        'staff'                 => 'Staff & Teacher Management',
        'salary_payroll'        => 'Salary & Payroll Management',

        // Finance & E-Commerce
        'online_fee_collection' => 'Online Fee Collection & Payments',
        'finance_orders'        => 'Order Management & Ledger',
        'invoices_receipts'     => 'Automated Tax Invoices & Billing',
        'financial_reports'     => 'Financial & Revenue Reports',

        // Website Builder & Themes
        'custom_domain'         => 'Custom Domain Mapping (SSL)',
        'website_builder'       => 'Website Builder & Visual Editor',
        'themes_and_styling'    => 'Theme Customizer & Color Schemes',
        'custom_pages'          => 'Custom Pages & Navigation Menus',
        'blogs_and_stories'     => 'Blogs, News & Story Publishing',
        'contents_manager'      => 'Media & Content Asset Manager',

        // Communication & CRM
        'inquiries_management'  => 'Inquiries & Lead Capturing',
        'newsletter_campaigns'  => 'Newsletter & Email Campaigns',
        'student_feedback'      => 'Student Feedback & Reviews',

        // Platform & Security
        'analytics'             => 'Advanced Analytics & Insights',
        'security_settings'     => 'Advanced Security Controls',
        'priority_support'      => 'Priority Support & Onboarding',
    ],

    /*
    |--------------------------------------------------------------------------
    | Resource Limits & Quotas
    |--------------------------------------------------------------------------
    |
    | Numerical thresholds configurable per subscription plan.
    |
    */
    'limits' => [
        'admin'     => 'Admin Limit',
        'teachers'  => 'Teachers Limit',
        'staff'     => 'Staff Members Limit',
        'students'  => 'Students Limit',
        'courses'   => 'Active Courses Limit',
        'storage'   => 'Storage (GB)',
    ],

];