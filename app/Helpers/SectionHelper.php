<?php

use App\Services\Section\SectionResolver;

if (!function_exists('sectionResolve')) {
    function sectionResolve(array $section): array
    {
        return (new SectionResolver($section))->resolve();
    }
}

if (!function_exists('section')) {
    function section($section)
    {
        return new \App\Services\Section\Section($section);
    }
}

if (!function_exists('block')) {
    function block($block)
    {
        return new \App\Services\Block\Block($block);
    }
}