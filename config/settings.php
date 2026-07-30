<?php

return [

    /*
|--------------------------------------------------------------------------
| SEO & Analytics
|--------------------------------------------------------------------------
*/
    "seo" => [
        "title" => "SEO & Search Optimization",
        "fields" => [
            "meta_title" => ["label" => "Default Meta Title", "type" => "text"],
            "meta_description" => ["label" => "Default Meta Description", "type" => "textarea"],
            "meta_keywords" => ["label" => "Default Meta Keywords", "type" => "text"],

            "google_analytics" => ["label" => "Google Analytics GA4 ID (G-XXXXXXXX)", "type" => "text"],
            "google_tag_manager" => ["label" => "Google Tag Manager ID (GTM-XXXXXXX)", "type" => "text"],
            "facebook_pixel_id" => ["label" => "Facebook Pixel ID", "type" => "text"],
            "microsoft_clarity_id" => ["label" => "Microsoft Clarity Project ID", "type" => "text"],
            
            "google_site_verification" => ["label" => "Google Site Verification Token", "type" => "text"],
            "bing_site_verification" => ["label" => "Bing Site Verification Token", "type" => "text"],

            "allow_indexing" => ["label" => "Allow Search Engine Indexing", "type" => "toggle", "default" => 1],
            "robots_meta" => ["label" => "Robots Meta Directives (e.g. index, follow)", "type" => "text", "default" => "index, follow"],
            "sitemap_enabled" => ["label" => "Enable Dynamic Sitemap.xml", "type" => "toggle", "default" => 1],
            "enable_internal_search" => ["label" => "Enable Public Site Search", "type" => "toggle", "default" => 1],
            
            "schema_org_json" => ["label" => "Custom Schema JSON-LD", "type" => "textarea"],
            "custom_head_tags" => ["label" => "Custom Header HTML Code (Scripts, Links, Meta)", "type" => "textarea"],
        ]
    ],


    /*
|--------------------------------------------------------------------------
| Contact Information
|--------------------------------------------------------------------------
*/
    "contact" => [
        "title" => "Contact Information",
        "fields" => [

            "phone" => ["label" => "Phone", "type" => "text", "default" => ""],
            "whatsapp" => ["label" => "WhatsApp", "type" => "text", "default" => ""],
            "email" => ["label" => "Email", "type" => "email", "default" => ""],

            "address" => ["label" => "Address", "type" => "textarea", "default" => ""],
            "address_map_link" => ["label" => "Google Map Link", "type" => "url", "default" => ""],

            "support_email" => ["label" => "Support Email", "type" => "email", "default" => ""],
            "sales_email" => ["label" => "Sales Email", "type" => "email", "default" => ""],

        ]
    ],

    /*
|--------------------------------------------------------------------------
| Social Media
|--------------------------------------------------------------------------
*/
    "social" => [
        "title" => "Social Media",
        "fields" => [

            "facebook_url" => ["label" => "Facebook", "type" => "url", "default" => ""],
            "instagram_url" => ["label" => "Instagram", "type" => "url", "default" => ""],
            "twitter_url" => ["label" => "Twitter / X", "type" => "url", "default" => ""],
            "linkedin_url" => ["label" => "LinkedIn", "type" => "url", "default" => ""],
            "youtube_url" => ["label" => "YouTube", "type" => "url", "default" => ""],
            "whatsapp_url" => ["label" => "WhatsApp", "type" => "url", "default" => ""],
            "telegram_url" => ["label" => "Telegram", "type" => "url", "default" => ""],

        ]
    ],

    /*
|--------------------------------------------------------------------------
| Website Behavior
|--------------------------------------------------------------------------
*/
    "website" => [
        "title" => "Platform Controls",
        "fields" => [
            "allow_registration" => ["label" => "Allow Registration", "type" => "toggle", "default" => 1],
            "email_verification" => ["label" => "Email Verification Required", "type" => "toggle", "default" => 1],
            "mobile_verification" => ["label" => "Mobile OTP Verification", "type" => "toggle", "default" => 0],

            "enable_courses" => ["label" => "Enable Courses", "type" => "toggle", "default" => 1],
            "enable_payments" => ["label" => "Enable Payments", "type" => "toggle", "default" => 1],

            "maintenance_mode" => ["label" => "Maintenance Mode", "type" => "toggle", "default" => 0],
        ]
    ],


    /*
|--------------------------------------------------------------------------
| Communication (Email, SMS, WhatsApp)
|--------------------------------------------------------------------------
*/
    "academics" => [
        "title" => "Course & Academics",
        "fields" => [
            "default_course_language" => [
                "label" => "Default Course Language",
                "type" => "select",
                "options" => ["english", "hindi"]
            ],

            "enable_modules" => ["label" => "Enable Modules", "type" => "toggle", "default" => 1],
            "enable_lessons" => ["label" => "Enable Lessons", "type" => "toggle", "default" => 1],
            "enable_quizzes" => ["label" => "Enable Quizzes", "type" => "toggle", "default" => 1],
            "enable_certificates" => ["label" => "Enable Certificates", "type" => "toggle", "default" => 1],
        ]
    ],


    /*
|--------------------------------------------------------------------------
| Payment Gateways
|--------------------------------------------------------------------------
*/
    "payments" => [
        "title" => "Payments",
        "fields" => [
            "payment_gateway" => [
                "label" => "Default Gateway",
                "type" => "select",
                "options" => ["razorpay", "stripe"]
            ],

            "razorpay_key" => ["label" => "Razorpay Key", "type" => "text"],
            "razorpay_secret" => ["label" => "Razorpay Secret", "type" => "password"],
        ]
    ],

    /*
|--------------------------------------------------------------------------
| Security
|--------------------------------------------------------------------------
*/
    "security" => [
        "title" => "Security",
        "fields" => [
            "enable_2fa" => ["label" => "Enable 2FA", "type" => "toggle"],
            "force_https" => ["label" => "Force HTTPS", "type" => "toggle", "default" => 1],
            "auto_logout_minutes" => ["label" => "Auto Logout (minutes)", "type" => "number", "default" => 60],
        ]
    ],


    /*
|--------------------------------------------------------------------------
| Communication
|--------------------------------------------------------------------------
*/
    "communication" => [
        "title" => "Email & Notifications",
        "fields" => [
            "smtp_host" => ["label" => "SMTP Host", "type" => "text"],
            "smtp_port" => ["label" => "SMTP Port", "type" => "number"],
            "smtp_username" => ["label" => "SMTP Username", "type" => "text"],
            "smtp_password" => ["label" => "SMTP Password", "type" => "password"],

            "notify_user_on_payment" => ["label" => "Notify User on Payment", "type" => "toggle", "default" => 1],
            "notify_admin_on_registration" => ["label" => "Notify Admin on Registration", "type" => "toggle", "default" => 1],
        ]
    ],

];
