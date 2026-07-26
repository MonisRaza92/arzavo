<?php

if (!function_exists('route_to')) {
    /**
     * Generate safe tenant-scoped URLs for developers to use in themes.
     * Supports both system pages and dynamic resources (categories, courses, blogs, etc.)
     *
     * @param string $type            e.g. 'login', 'courses', 'category', 'course_cards'
     * @param mixed|null $slugOrModel Model object, slug string, or numeric ID
     * @return string
     */
    function route_to(string $type, $slugOrModel = null): string
    {
        // Normalize type (remove card suffixes and standard plurals)
        $cleanType = str_replace(
            ['tenant.', '_cards', '_card', 's_cards', 's_card'],
            '',
            strtolower($type)
        );

        // 1️⃣ Handle system pages without slug
        if ($slugOrModel === null) {
            return match ($cleanType) {
                'home' => route('tenant.home'),
                'about' => route('tenant.about'),
                'courses' => route('tenant.courses'),
                'blogs' => route('tenant.blogs'),
                'content-store', 'content_store' => route('tenant.content-store'),
                'privacy-policy', 'privacy_policy' => route('tenant.privacy-policy'),
                'terms-conditions', 'terms_conditions' => route('tenant.terms-conditions'),
                'login' => route('tenant.login'),
                'register' => route('tenant.register'),
                'logout' => route('tenant.logout'),
                'profile' => route('profile'),
                'dashboard' => route('user-dashboard'),
                default => url('/' . $cleanType),
            };
        }

        // 2️⃣ Resolve ID and Slug from dynamic resource
        $id = null;
        $slug = null;

        if (is_object($slugOrModel)) {
            $id = $slugOrModel->id ?? null;
            $slug = $slugOrModel->slug ?? null;

            // Auto-detect type based on model class name
            $className = class_basename($slugOrModel);
            $cleanType = match ($className) {
                'AcademicCategory' => 'category',
                'ClassCourse' => 'class',
                'Subject' => 'subject',
                'Course' => 'course',
                'Blog' => 'blog',
                default => $cleanType,
            };
        } elseif (is_numeric($slugOrModel)) {
            $id = $slugOrModel;
        } else {
            $slug = $slugOrModel;
        }

        // 3️⃣ Generate correct resource route
        return match ($cleanType) {
            'categories' => route('tenant.courses'),
            'category' => route('tenant.courses') . '?' . ($slug ? "category={$slug}" : "category_id={$id}"),
            'class' => route('tenant.courses') . '?' . ($slug ? "class={$slug}" : "class_id={$id}"),
            'subject' => route('tenant.courses') . '?' . ($slug ? "subject={$slug}" : "subject_id={$id}"),
            'course' => route('tenant.course') . '?' . ($slug ? "slug={$slug}" : "id={$id}"),
            'blog' => route('tenant.blog') . '?' . ($slug ? "slug={$slug}" : "id={$id}"),
            'content' => route('tenant.content') . '?' . ($slug ? "slug={$slug}" : "id={$id}"),
            default => url('/' . $cleanType . '/' . ($slug ?? $id)),
        };
    }
}