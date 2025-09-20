<?php

class DPC_Hooks {
    public static function init_hooks() {
        // Serve cache sớm nhất có thể
        if (!is_admin() && $_SERVER['REQUEST_METHOD'] === 'GET') {
            add_action('template_redirect', [__CLASS__, 'serve_cache'], 0);
        }

        // Hook xóa cache khi thay đổi nội dung
        add_action('save_post', [__CLASS__, 'invalidate_post_cache']);
        add_action('wp_update_nav_menu', [__CLASS__, 'invalidate_menu_cache']);

        // Hook sau khi hiển thị để lưu
        add_action('shutdown', [__CLASS__, 'maybe_store_cache']);
    }

    public static function serve_cache() {
        $url = self::get_current_url();
        $cached = DPC_Cache_Manager::get_cache($url);

        if ($cached) {
            echo "<!-- Served from Dynamic Page Cache -->\n" . $cached;
            exit;
        }
    }

    public static function maybe_store_cache() {
        if (!is_singular() && !is_front_page()) return;

        ob_start(function ($html) {
            $url = self::get_current_url();
            DPC_Cache_Manager::save_cache($url, $html);
            return $html;
        });
    }

    public static function invalidate_post_cache($post_id) {
        $url = get_permalink($post_id);
        DPC_Cache_Manager::delete_cache($url);
    }

    public static function invalidate_menu_cache($menu_id) {
        DPC_Cache_Manager::clear_all();
    }

    private static function get_current_url() {
        return home_url(add_query_arg([], $_SERVER['REQUEST_URI']));
    }
}