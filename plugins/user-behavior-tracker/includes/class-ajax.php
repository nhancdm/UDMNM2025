<?php

class UBT_AJAX {
    public static function init() {
        add_action('wp_ajax_nopriv_ubt_track', [__CLASS__, 'handle']);
    }

    public static function handle() {
        check_ajax_referer('ubt_nonce', 'nonce');

        $data = [
            'page_url' => esc_url_raw($_POST['url']),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'clicks' => intval($_POST['clicks']),
            'scroll_depth' => intval($_POST['scroll']),
            'time_spent' => intval($_POST['time']),
        ];

        UBT_DB::insert_log($data);
        wp_send_json_success();
    }
}

UBT_AJAX::init();