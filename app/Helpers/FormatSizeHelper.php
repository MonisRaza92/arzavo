<?php

if (! function_exists('formatSize')) {

    function formatSize($bytes, $precision = 2)
    {
        if (!is_numeric($bytes) || $bytes <= 0) {
            return '0 KB';
        }

        $kb = 1024;
        $mb = $kb * 1024;
        $gb = $mb * 1024;
        $tb = $gb * 1024;

        if ($bytes < $mb) {
            return round($bytes / $kb, $precision) . ' KB';
        }

        if ($bytes < $gb) {
            return round($bytes / $mb, $precision) . ' MB';
        }

        if ($bytes < $tb) {
            return round($bytes / $gb, $precision) . ' GB';
        }

        return round($bytes / $tb, $precision) . ' TB';
    }
}
