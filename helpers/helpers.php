<?php


use ShamarKellman\Settings\Facades\Setting;

if (!function_exists('setting')) {
    /**
     * @param string|array $key
     * @param null         $default
     */
    function setting($key, $default = null)
    {
        return Setting::get($key, $default);
    }
}
