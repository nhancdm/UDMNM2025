<?php
/**
 * Plugin Name: User Behavior Tracker
 * Description: Phân tích hành vi người dùng (scroll, click, time) và báo cáo trong admin.
 * Version: 1.0
 * Author: Bạn
 */

if (!defined('ABSPATH')) exit;

define('UBT_DIR', plugin_dir_path(__FILE__));
define('UBT_URL', plugin_dir_url(__FILE__));

require_once UBT_DIR . 'includes/class-db.php';
require_once UBT_DIR . 'includes/class-ajax.php';
require_once UBT_DIR . 'includes/class-admin-report.php';
require_once UBT_DIR . 'includes/class-gdpr.php';

register_activation_hook(__FILE__, ['UBT_DB', 'create_table']);

add_action('wp_enqueue_scripts', function () {
    if (!is_user_logged_in()) {
        wp_enqueue_script('ubt-tracker', UBT_URL . 'js/tracker.js', [], null, true);
        wp_enqueue_style('ubt-gdpr', UBT_URL . 'css/gdpr.css');
        wp_localize_script('ubt-tracker', 'UBT_AJAX', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ubt_nonce'),
        ]);
    }
});

add_action('wp_footer', ['UBT_GDPR', 'render_consent_box']);