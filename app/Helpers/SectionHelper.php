<?php

use App\Services\Section\SectionResolver;

if (! function_exists('section')) {
    function section(array $section): array
    {
        return (new SectionResolver($section))->resolve();
    }
}