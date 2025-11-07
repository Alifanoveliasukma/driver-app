<?php

if (!function_exists('highlightText')) {
    function highlightText($text, $search) {
        if (empty($text) || empty($search)) return $text;

        $safeSearch = preg_quote($search, '/');

        $highlighted = preg_replace("/($safeSearch)/i", '<mark class=\"bg-warning\">$1</mark>', $text);

        return $highlighted ?: $text;
    }
}