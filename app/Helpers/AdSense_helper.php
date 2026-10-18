<?php

/**
 * AdSense Helper
 *
 * Membaca konfigurasi dari tabel adsense_settings.
 * Script adsbygoogle.js dimuat SEKALI di <head> via get_adsense_head_script().
 * Setiap fungsi display hanya render tag <ins> tanpa memuat ulang script.
 */

if (!function_exists('get_adsense')) {
    function get_adsense(): object
    {
        static $cfg = null;
        if ($cfg === null) {
            $cfg = (new \App\Models\AdsenseSetting())->getSettings();
        }
        return $cfg;
    }
}

if (!function_exists('get_adsense_head_script')) {
    /**
     * Dipanggil SEKALI di <head> layout.
     * Hanya render jika AdSense aktif dan publisher_id terisi.
     */
    function get_adsense_head_script(): string
    {
        $cfg = get_adsense();
        if (!$cfg->is_active || empty($cfg->publisher_id)) return '';

        return '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client='
            . esc($cfg->publisher_id)
            . '" crossorigin="anonymous"></script>' . "\n";
    }
}

if (!function_exists('display_header_ad')) {
    function display_header_ad(): string
    {
        $cfg = get_adsense();
        if (!$cfg->is_active || !$cfg->show_header || empty($cfg->publisher_id) || empty($cfg->slot_header)) {
            return '';
        }
        return '<div class="header-ad text-center my-3">'
            . _adsense_ins($cfg->publisher_id, $cfg->slot_header, 'auto', true)
            . '</div>';
    }
}

if (!function_exists('display_sidebar_ad')) {
    function display_sidebar_ad(): string
    {
        $cfg = get_adsense();
        if (!$cfg->is_active || !$cfg->show_sidebar || empty($cfg->publisher_id) || empty($cfg->slot_sidebar)) {
            return '';
        }
        return '<div class="sidebar-ad mb-4">'
            . _adsense_ins($cfg->publisher_id, $cfg->slot_sidebar, 'auto', true)
            . '</div>';
    }
}

if (!function_exists('display_content_ad')) {
    function display_content_ad(): string
    {
        $cfg = get_adsense();
        if (!$cfg->is_active || !$cfg->show_content || empty($cfg->publisher_id) || empty($cfg->slot_content)) {
            return '';
        }
        return '<div class="content-ad my-4 text-center">'
            . _adsense_ins($cfg->publisher_id, $cfg->slot_content, 'auto', true)
            . '</div>';
    }
}

if (!function_exists('_adsense_ins')) {
    /**
     * Render tag <ins> AdSense + push script.
     * Script adsbygoogle.js sudah dimuat di <head>, tidak dimuat ulang di sini.
     */
    function _adsense_ins(string $pubId, string $slot, string $format = 'auto', bool $responsive = true): string
    {
        $resp = $responsive ? 'data-full-width-responsive="true"' : '';
        return '<ins class="adsbygoogle" style="display:block"'
            . ' data-ad-client="' . esc($pubId) . '"'
            . ' data-ad-slot="' . esc($slot) . '"'
            . ' data-ad-format="' . esc($format) . '"'
            . ' ' . $resp . '></ins>'
            . '<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>';
    }
}
