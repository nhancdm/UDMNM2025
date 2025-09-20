<?php
/**
 * Plugin Name: AB Content Tester
 * Description: Plugin tạo hệ thống A/B Testing cho tiêu đề và nội dung.
 * Version: 1.0
 */

define('ABT_PATH', plugin_dir_path(__FILE__));
define('ABT_URL', plugin_dir_url(__FILE__));

// Gọi file cần thiết
include_once ABT_PATH . 'includes/ab-admin-page.php';
include_once ABT_PATH . 'includes/ab-tracking-endpoint.php';

// Tạo table lưu dữ liệu tracking
register_activation_hook(__FILE__, function () {
    global $wpdb;
    $table = $wpdb->prefix . 'abt_data';
    $charset = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table (
        id BIGINT AUTO_INCREMENT PRIMARY KEY,
        post_id BIGINT,
        variation VARCHAR(255),
        metric VARCHAR(50),
        value FLOAT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) $charset;";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
});

// Enqueue JS tracking
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('abt-tracker', ABT_URL . 'js/tracker.js', [], null, true);
    wp_localize_script('abt-tracker', 'abt_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
});