<?php

if (!function_exists('colors')) {

    function colors(
        $colors = null,
        $primaryBtn = null,
        $secondaryBtn = null,
        $link = null
    ) {
        $css = '';

        // 🎨 Base colors
        if ($colors) {
            $css .= "--arzavo-background: {$colors->background};";
            $css .= "--arzavo-border-color: {$colors->border};";
            $css .= "--arzavo-heading-color: {$colors->heading};";
            $css .= "--arzavo-paragraph-color: {$colors->paragraph};";
            $css .= "--arzavo-secondary-text-color: {$colors->secondary_text};";
            $css .= "--arzavo-link-color: {$colors->link};";
            $css .= "--arzavo-link-hover-color: {$colors->link_hover};";
        }

        // 🔘 Primary button
        if ($primaryBtn) {
            $css .= "--arzavo-primary-btn-background: {$primaryBtn->background};";
            $css .= "--arzavo-primary-btn-text-color: {$primaryBtn->text};";
            $css .= "--arzavo-primary-btn-border: {$primaryBtn->border};";
            $css .= "--arzavo-primary-btn-hover-background: {$primaryBtn->hover_background};";
            $css .= "--arzavo-primary-btn-hover-text: {$primaryBtn->hover_text};";
            $css .= "--arzavo-primary-btn-hover-border: {$primaryBtn->hover_border};";
        }

        // 🔘 Secondary button
        if ($secondaryBtn) {
            $css .= "--arzavo-secondary-btn-background: {$secondaryBtn->background};";
            $css .= "--arzavo-secondary-btn-text-color: {$secondaryBtn->text};";
            $css .= "--arzavo-secondary-btn-border: {$secondaryBtn->border};";
            $css .= "--arzavo-secondary-btn-hover-background: {$secondaryBtn->hover_background};";
            $css .= "--arzavo-secondary-btn-hover-text: {$secondaryBtn->hover_text};";
            $css .= "--arzavo-secondary-btn-hover-border: {$secondaryBtn->hover_border};";
        }

        // 🔗 Link button
        if ($link) {
            $css .= "--arzavo-link-btn-text-color: {$link->text};";
            $css .= "--arzavo-link-btn-hover-text-color: {$link->hover_text};";
        }

        return $css;
    }
}
