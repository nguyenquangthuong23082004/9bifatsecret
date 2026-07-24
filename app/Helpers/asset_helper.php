<?php

if (! function_exists('asset_url')) {
    /**
     * Return the asset URL.
     *
     * @param string $path
     * @return string
     */
    function asset_url(string $path = ''): string
    {
        return base_url('assets/' . ltrim($path, '/'));
    }
}
