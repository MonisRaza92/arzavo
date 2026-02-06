<?php

use App\Models\Tenant\ColorScheme;

if (!function_exists('scheme')) {

    /**
     * Generate CSS variables for a color scheme
     *
     * @param string|null $key        e.g. scheme_1
     * @param int|null    $themeId    e.g. $theme->theme_id
     */
    function scheme($key)
    {
        $themeId = app('currentThemeId') ?? null;

        if (!$key || !$themeId) {
            return '';
        }

        // dd($key, $themeId);
        // ✅ Resolve scheme by theme_id + key (NOT by id)
        $scheme = ColorScheme::where('theme_id', $themeId)
            ->where('key', $key)
            ->first();

        if (!$scheme) {
            return '';
        }

        // ⚠️ colors structure AS-IS (no change)
        $colors = $scheme->scheme_colors;
        $primaryBtn = $scheme->primary_btn;
        $secondaryBtn = $scheme->secondary_btn;
        $input = $scheme->input;

        $css = '';

        // 🎨 Base colors
        if ($colors) {
            $css .= "--arzavo-background: {$colors->background};";
            $css .= "--arzavo-border-color: {$colors->border};";
            $css .= "--arzavo-heading-color: {$colors->heading};";
            $css .= "--arzavo-subheading-color: {$colors->subheading};";
            $css .= "--arzavo-paragraph-color: {$colors->paragraph};";
            $css .= "--arzavo-secondary-text-color: {$colors->secondary_text};";
            $css .= "--arzavo-invert-text-color: {$colors->invert_text};";
            $css .= "--arzavo-link-color: {$colors->link};";
            $css .= "--arzavo-link-hover-color: {$colors->link_hover};";
        }

        // 🔘 Primary button
        if ($primaryBtn) {
            $css .= "--arzavo-primary-btn-background: {$primaryBtn->background};";
            $css .= "--arzavo-primary-btn-text: {$primaryBtn->text};";
            $css .= "--arzavo-primary-btn-border: {$primaryBtn->border};";
            $css .= "--arzavo-primary-btn-hover-background: {$primaryBtn->hover_background};";
            $css .= "--arzavo-primary-btn-hover-text: {$primaryBtn->hover_text};";
            $css .= "--arzavo-primary-btn-hover-border: {$primaryBtn->hover_border};";
        }

        // 🔘 Secondary button
        if ($secondaryBtn) {
            $css .= "--arzavo-secondary-btn-background: {$secondaryBtn->background};";
            $css .= "--arzavo-secondary-btn-text: {$secondaryBtn->text};";
            $css .= "--arzavo-secondary-btn-border: {$secondaryBtn->border};";
            $css .= "--arzavo-secondary-btn-hover-background: {$secondaryBtn->hover_background};";
            $css .= "--arzavo-secondary-btn-hover-text: {$secondaryBtn->hover_text};";
            $css .= "--arzavo-secondary-btn-hover-border: {$secondaryBtn->hover_border};";
        }

        // 🧾 Input
        if ($input) {
            $css .= "--arzavo-input-background: {$input->background};";
            $css .= "--arzavo-input-text: {$input->text};";
            $css .= "--arzavo-input-border: {$input->border};";
            $css .= "--arzavo-input-focus-border: {$input->focus_border};";
        }

        return $css;
    }
}
