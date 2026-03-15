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
        $primaryBtn = $scheme->primary_btn;
        $secondaryBtn = $scheme->secondary_btn;
        $input = $scheme->input;

        $css = '';

        // 🎨 Base colors
        if ($colors) {
            $css .= "--arz-bg: " . ($colors->background ?? '#ffffff') . ";";
            $css .= "--arz-border: " . ($colors->border ?? '#cccccc') . ";";
            $css .= "--arz-heading: " . ($colors->heading ?? '#000000') . ";";
            $css .= "--arz-subheading: " . ($colors->subheading ?? '#333333') . ";";
            $css .= "--arz-paragraph: " . ($colors->paragraph ?? '#666666') . ";";
            $css .= "--arz-body-text: " . ($colors->secondary_text ?? '#999999') . ";";
            $css .= "--arz-link: " . ($colors->link ?? '#0066cc') . ";";
            $css .= "--arz-link-hover: " . ($colors->link_hover ?? '#004499') . ";";
        }

        // 🔘 Primary button
        if ($primaryBtn) {
            $css .= "--arz-btn-bg: " . ($primaryBtn->background ?? '#007bff') . ";";
            $css .= "--arz-btn-text: " . ($primaryBtn->text ?? '#ffffff') . ";";
            $css .= "--arz-btn-border: " . ($primaryBtn->border ?? '#007bff') . ";";
            $css .= "--arz-btn-hover-bg: " . ($primaryBtn->hover_background ?? '#0056b3') . ";";
            $css .= "--arz-btn-hover-text: " . ($primaryBtn->hover_text ?? '#ffffff') . ";";
            $css .= "--arz-btn-hover-border: " . ($primaryBtn->hover_border ?? '#0056b3') . ";";
        }

        // 🔘 Secondary button
        if ($secondaryBtn) {
            $css .= "--arz-btn2-bg: " . ($secondaryBtn->background ?? '#6c757d') . ";";
            $css .= "--arz-btn2-text: " . ($secondaryBtn->text ?? '#ffffff') . ";";
            $css .= "--arz-btn2-border: " . ($secondaryBtn->border ?? '#6c757d') . ";";
            $css .= "--arz-btn2-hover-bg: " . ($secondaryBtn->hover_background ?? '#545b62') . ";";
            $css .= "--arz-btn2-hover-text: " . ($secondaryBtn->hover_text ?? '#ffffff') . ";";
            $css .= "--arz-btn2-hover-border: " . ($secondaryBtn->hover_border ?? '#545b62') . ";";
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
