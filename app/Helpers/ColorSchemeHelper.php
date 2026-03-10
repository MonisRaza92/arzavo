<?php


if (!function_exists('scheme')) {

    /**
     * Generate CSS variables for a color scheme
     *
     * @param string|null $key        e.g. scheme_1
     * @param int|null    $themeId    e.g. $theme->theme_id
     */
    function scheme($key = 'scheme_1')
    {

        $schemes = app('view')->getShared()['colorSchemes'] ?? null;


        if (!$schemes || !isset($schemes[$key])) {
            return '';
        }

        $scheme = $schemes[$key];

        // ⚠️ colors structure AS-IS (no change)
        $colors = $scheme->scheme_colors;
        dd($colors);
        $primaryBtn = $scheme->primary_btn;
        $secondaryBtn = $scheme->secondary_btn;
        $input = $scheme->input;

        $css = '';

        // 🎨 Base colors
        if ($colors) {
            $css .= "--arzavo-background: " . ($colors->background ?? '#ffffff') . ";";
            $css .= "--arzavo-border-color: " . ($colors->border ?? '#cccccc') . ";";
            $css .= "--arzavo-heading-color: " . ($colors->heading ?? '#000000') . ";";
            $css .= "--arzavo-subheading-color: " . ($colors->subheading ?? '#333333') . ";";
            $css .= "--arzavo-paragraph-color: " . ($colors->paragraph ?? '#666666') . ";";
            $css .= "--arzavo-secondary-text-color: " . ($colors->secondary_text ?? '#999999') . ";";
            $css .= "--arzavo-invert-text-color: " . ($colors->invert_text ?? '#ffffff') . ";";
            $css .= "--arzavo-link-color: " . ($colors->link ?? '#0066cc') . ";";
            $css .= "--arzavo-link-hover-color: " . ($colors->link_hover ?? '#004499') . ";";
        }

        // 🔘 Primary button
        if ($primaryBtn) {
            $css .= "--arzavo-primary-btn-background: " . ($primaryBtn->background ?? '#007bff') . ";";
            $css .= "--arzavo-primary-btn-text: " . ($primaryBtn->text ?? '#ffffff') . ";";
            $css .= "--arzavo-primary-btn-border: " . ($primaryBtn->border ?? '#007bff') . ";";
            $css .= "--arzavo-primary-btn-hover-background: " . ($primaryBtn->hover_background ?? '#0056b3') . ";";
            $css .= "--arzavo-primary-btn-hover-text: " . ($primaryBtn->hover_text ?? '#ffffff') . ";";
            $css .= "--arzavo-primary-btn-hover-border: " . ($primaryBtn->hover_border ?? '#0056b3') . ";";
        }

        // 🔘 Secondary button
        if ($secondaryBtn) {
            $css .= "--arzavo-secondary-btn-background: " . ($secondaryBtn->background ?? '#6c757d') . ";";
            $css .= "--arzavo-secondary-btn-text: " . ($secondaryBtn->text ?? '#ffffff') . ";";
            $css .= "--arzavo-secondary-btn-border: " . ($secondaryBtn->border ?? '#6c757d') . ";";
            $css .= "--arzavo-secondary-btn-hover-background: " . ($secondaryBtn->hover_background ?? '#545b62') . ";";
            $css .= "--arzavo-secondary-btn-hover-text: " . ($secondaryBtn->hover_text ?? '#ffffff') . ";";
            $css .= "--arzavo-secondary-btn-hover-border: " . ($secondaryBtn->hover_border ?? '#545b62') . ";";
        }

        // 🧾 Input
        if ($input) {
            $css .= "--arzavo-input-background: " . ($input->background ?? '#ffffff') . ";";
            $css .= "--arzavo-input-text: " . ($input->text ?? '#000000') . ";";
            $css .= "--arzavo-input-border: " . ($input->border ?? '#cccccc') . ";";
            $css .= "--arzavo-input-focus-border: " . ($input->focus_border ?? '#007bff') . ";";
        }

        return $css;
    }
}
