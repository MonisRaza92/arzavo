<?php

return [

    /*
|--------------------------------------------------------------------------
| SEO & Analytics
|--------------------------------------------------------------------------
*/
    "seo" => [
        "title" => "SEO & Analytics",
        "fields" => [

            "meta_title" => ["label" => "Meta Title", "type" => "text", "default" => ""],
            "meta_description" => ["label" => "Meta Description", "type" => "textarea", "default" => ""],
            "meta_keywords" => ["label" => "Meta Keywords", "type" => "text", "default" => ""],

            "google_analytics" => ["label" => "Google Analytics ID", "type" => "text", "default" => ""],
            "google_tag_manager" => ["label" => "Google Tag Manager ID", "type" => "text", "default" => ""],
            "facebook_pixel" => ["label" => "Facebook Pixel ID", "type" => "text", "default" => ""],

            "og_title" => ["label" => "OpenGraph Title", "type" => "text", "default" => ""],
            "og_description" => ["label" => "OpenGraph Description", "type" => "textarea", "default" => ""],
            "og_image" => ["label" => "OpenGraph Image URL", "type" => "image", "default" => ""],

            "allow_indexing" => ["label" => "Allow Search Engine Indexing", "type" => "toggle", "default" => 1],
            "sitemap_enabled" => ["label" => "Enable Sitemap.xml", "type" => "toggle", "default" => 1],

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
        "title" => "Website Controls",
        "fields" => [

            "allow_registration" => ["label" => "Allow Registration", "type" => "toggle", "default" => 1],
            "email_verification" => ["label" => "Email Verification Required", "type" => "toggle", "default" => 1],
            "mobile_verification" => ["label" => "Mobile OTP Verification", "type" => "toggle", "default" => 0],

            "enable_blog" => ["label" => "Enable Blog", "type" => "toggle", "default" => 1],
            "enable_events" => ["label" => "Enable Events", "type" => "toggle", "default" => 1],
            "enable_courses" => ["label" => "Enable Courses", "type" => "toggle", "default" => 1],
            "enable_admissions" => ["label" => "Enable Admissions", "type" => "toggle", "default" => 1],
            "enable_payments" => ["label" => "Enable Payments", "type" => "toggle", "default" => 1],

            "maintenance_mode" => ["label" => "Maintenance Mode", "type" => "toggle", "default" => 0],
            "coming_soon" => ["label" => "Coming Soon Mode", "type" => "toggle", "default" => 0],

            "cookie_consent" => ["label" => "Cookie Consent Banner", "type" => "toggle", "default" => 1],

        ]
    ],

    /*
|--------------------------------------------------------------------------
| Communication (Email, SMS, WhatsApp)
|--------------------------------------------------------------------------
*/
    "communication" => [
        "title" => "Communication",
        "fields" => [

            "smtp_host" => ["label" => "SMTP Host", "type" => "text", "default" => ""],
            "smtp_port" => ["label" => "SMTP Port", "type" => "text", "default" => ""],
            "smtp_username" => ["label" => "SMTP Username", "type" => "text", "default" => ""],
            "smtp_password" => ["label" => "SMTP Password", "type" => "password", "default" => ""],
            "smtp_encryption" => ["label" => "SMTP Encryption", "type" => "select", "options" => ["ssl", "tls", "none"], "default" => "tls"],

            "sms_provider" => ["label" => "SMS Provider", "type" => "select", "options" => ["twilio", "msg91", "fast2sms"], "default" => ""],
            "sms_api_key" => ["label" => "SMS API Key", "type" => "text", "default" => ""],
            "sms_sender_id" => ["label" => "SMS Sender ID", "type" => "text", "default" => ""],

            "notify_user_on_payment" => ["label" => "Notify User on Payment", "type" => "toggle", "default" => 1],
            "notify_admin_on_registration" => ["label" => "Notify Admin on Registration", "type" => "toggle", "default" => 1],

        ]
    ],

    /*
|--------------------------------------------------------------------------
| Payment Gateways
|--------------------------------------------------------------------------
*/
    "payments" => [
        "title" => "Payment Gateways",
        "fields" => [

            "payment_gateway" => ["label" => "Default Gateway", "type" => "select", "options" => ["razorpay", "stripe", "paypal"], "default" => "razorpay"],

            "razorpay_key" => ["label" => "Razorpay Key", "type" => "text", "default" => ""],
            "razorpay_secret" => ["label" => "Razorpay Secret", "type" => "password", "default" => ""],

            "stripe_key" => ["label" => "Stripe Publishable Key", "type" => "text", "default" => ""],
            "stripe_secret" => ["label" => "Stripe Secret", "type" => "password", "default" => ""],

            "paypal_client_id" => ["label" => "PayPal Client ID", "type" => "text", "default" => ""],
            "paypal_secret" => ["label" => "PayPal Secret", "type" => "password", "default" => ""],
            "paypal_mode" => ["label" => "PayPal Mode", "type" => "select", "options" => ["sandbox", "live"], "default" => "sandbox"],

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

            "enable_2fa" => ["label" => "Enable Two Factor Authentication", "type" => "toggle", "default" => 0],
            "force_https" => ["label" => "Force HTTPS", "type" => "toggle", "default" => 1],
            "captcha_on_login" => ["label" => "Captcha on Login", "type" => "toggle", "default" => 1],
            "block_vpn" => ["label" => "Block VPN Users", "type" => "toggle", "default" => 0],
            "auto_logout_minutes" => ["label" => "Auto Logout After (minutes)", "type" => "number", "default" => 60],

        ]
    ],

    /*
|--------------------------------------------------------------------------
| Appearance
|--------------------------------------------------------------------------
*/
    "appearance" => [
        "title" => "Appearance",
        "fields" => [

            "site_logo" => ["label" => "Site Logo", "type" => "image", "default" => ""],
            "site_favicon" => ["label" => "Favicon", "type" => "image", "default" => ""],

            "primary_color" => ["label" => "Primary Color", "type" => "color", "default" => "#2563eb"],
            "secondary_color" => ["label" => "Secondary Color", "type" => "color", "default" => "#1e293b"],

            "font_family" => ["label" => "Font Family", "type" => "select", "options" => ["Inter", "Poppins", "Roboto"], "default" => "Inter"],

        ]
    ],

    /*
|--------------------------------------------------------------------------
| Footer
|--------------------------------------------------------------------------
*/
    "footer" => [
        "title" => "Footer",
        "fields" => [

            "footer_text" => ["label" => "Footer Text", "type" => "text", "default" => ""],
            "privacy_policy_url" => ["label" => "Privacy Policy URL", "type" => "url", "default" => ""],
            "terms_url" => ["label" => "Terms & Conditions URL", "type" => "url", "default" => ""],
            "auto_update_year" => ["label" => "Auto Update Copyright Year", "type" => "toggle", "default" => 1],

        ]
    ],

];
