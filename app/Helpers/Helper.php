<?php

function formatBytes(int $bytes, int $decimals = 2): string
{
    if ($bytes >= 1073741824) {
        return round($bytes / 1073741824, $decimals) . ' GB';
    } elseif ($bytes >= 1048576) {
        return round($bytes / 1048576, $decimals) . ' MB';
    } elseif ($bytes >= 1024) {
        return round($bytes / 1024, $decimals) . ' KB';
    }
    return $bytes . ' B';
}
