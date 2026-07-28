<?php

if (!function_exists('route_to')) {
    /**
     * Generate safe tenant-scoped URLs for theme developers.
     *
     * System pages  → have explicit named routes
     * Non-system pages (contact, privacy-policy, terms-conditions …)
     *               → served via /{slug} generic route — url() is enough
     * Actions       → contact.form, newsletter.submit
     * Resources     → categories, courses, blogs, books, book-categories …
     *
     * @param string $type           'home' | 'courses' | 'category' | 'book.category' | …
     * @param mixed|null $slugOrModel Model object, slug string, or numeric ID
     * @return string
     */
    function route_to(string $type, $slugOrModel = null): string
    {
        // Normalise: strip card suffixes / tenant prefix added by the builder
        $cleanType = str_replace(
            ['tenant.', '_cards', '_card', 's_cards', 's_card'],
            '',
            strtolower($type)
        );

        // ── Strict whitelist ──────────────────────────────────────────
        // Only types listed here can generate URLs from theme templates.
        $allowedTypes = [
            // System page slugs
            'home', 'courses', 'blogs',
            'book-categories', 'book_categories', 'bookcategories',
            'books',

            // Dynamic resource types
            'categories', 'category',
            'class', 'subject', 'course', 'blog',
            'book.category', 'book_category', 'bookcategory', 'book',

            // Auth
            'login', 'register', 'logout', 'profile', 'dashboard',

            // Action forms
            'contact.form', 'contact_form',
            'newsletter.submit', 'newsletter_submit',

            // Non-system pages (served via /{slug}, url() is used)
            'contact', 'privacy-policy', 'privacy_policy',
            'terms-conditions', 'terms_conditions', 'about',
        ];

        if (!in_array($cleanType, $allowedTypes)) {
            return '#';
        }

        // ── 1. No slug → system page or action URL ───────────────────
        if ($slugOrModel === null) {
            return match ($cleanType) {
                // System pages (have named routes)
                'home'                                       => route('tenant.home'),
                'courses'                                    => route('tenant.courses'),
                'blogs'                                      => route('tenant.blogs'),
                'book-categories', 'book_categories',
                'bookcategories'                             => route('tenant.book-categories'),
                'books'                                      => route('tenant.books'),

                // Auth
                'login'                                      => route('tenant.login'),
                'register'                                   => route('tenant.register'),
                'logout'                                     => route('tenant.logout'),
                'profile'                                    => route('profile'),
                'dashboard'                                  => route('user-dashboard'),

                // Action forms
                'contact.form', 'contact_form'               => route('contact.form'),
                'newsletter.submit', 'newsletter_submit'     => route('newsletter.submit'),

                // Non-system pages → generic /{slug} URL
                'contact'                                    => url('/contact'),
                'privacy-policy', 'privacy_policy'          => url('/privacy-policy'),
                'terms-conditions', 'terms_conditions'       => url('/terms-conditions'),
                'about'                                      => url('/about'),

                default => url('/' . $cleanType),
            };
        }

        // ── 2. Resolve slug/id from model or raw value ───────────────
        $id   = null;
        $slug = null;

        if (is_object($slugOrModel)) {
            $id   = $slugOrModel->id   ?? null;
            $slug = $slugOrModel->slug ?? null;

            // Auto-detect resource type from Eloquent model class
            $cleanType = match (class_basename($slugOrModel)) {
                'AcademicCategory' => 'category',
                'ClassCourse'      => 'class',
                'Subject'          => 'subject',
                'Course'           => 'course',
                'Blog'             => 'blog',
                'BookCategory'     => 'book.category',
                'Book'             => 'book',
                default            => $cleanType,
            };
        } elseif (is_numeric($slugOrModel)) {
            $id = $slugOrModel;
        } else {
            $slug = $slugOrModel;
        }

        // ── 3. Generate resource URL ─────────────────────────────────
        return match ($cleanType) {
            // Course ecosystem
            'categories'  => route('tenant.courses'),
            'category'    => route('tenant.courses') . '?' . ($slug ? "category={$slug}"   : "category_id={$id}"),
            'class'       => route('tenant.courses') . '?' . ($slug ? "class={$slug}"       : "class_id={$id}"),
            'subject'     => route('tenant.courses') . '?' . ($slug ? "subject={$slug}"     : "subject_id={$id}"),
            'course'      => route('tenant.course')  . '?' . ($slug ? "slug={$slug}"        : "id={$id}"),

            // Blog
            'blog'        => route('tenant.blog')    . '?' . ($slug ? "slug={$slug}"        : "id={$id}"),

            // Book ecosystem
            'book.category', 'book_category', 'bookcategory'
                          => route('tenant.books')   . '?' . ($slug ? "book_category={$slug}" : "book_category_id={$id}"),
            'book'        => route('tenant.books')   . '?' . ($slug ? "slug={$slug}"        : "id={$id}"),

            default       => url('/' . $cleanType . '/' . ($slug ?? $id)),
        };
    }
}