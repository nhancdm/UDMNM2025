<?php

class DPC_Cache_Manager {
    public static function get_cache_key($url) {
        return md5($url);
    }

    public static function save_cache($url, $html) {
        $key = self::get_cache_key($url);
        $mode = get_option('dpc_cache_mode', 'file');

        if ($mode === 'file') {
            file_put_contents(DPC_CACHE_DIR . $key . '.html', $html);
        } else {
            set_transient('dpc_cache_' . $key, $html, DAY_IN_SECONDS);
        }
    }

    public static function get_cache($url) {
        $key = self::get_cache_key($url);
        $mode = get_option('dpc_cache_mode', 'file');

        if ($mode === 'file') {
            $file = DPC_CACHE_DIR . $key . '.html';
            return file_exists($file) ? file_get_contents($file) : false;
        } else {
            return get_transient('dpc_cache_' . $key);
        }
    }

    public static function delete_cache($url) {
        $key = self::get_cache_key($url);
        $mode = get_option('dpc_cache_mode', 'file');

        if ($mode === 'file') {
            $file = DPC_CACHE_DIR . $key . '.html';
            if (file_exists($file)) unlink($file);
        } else {
            delete_transient('dpc_cache_' . $key);
        }
    }

    public static function clear_all() {
        $files = glob(DPC_CACHE_DIR . '*.html');
        foreach ($files as $file) unlink($file);
    }
}