<?php
/**
 * Plugin Name: WooCommerce API Sync
 * Description: Đồng bộ sản phẩm WooCommerce với API bên thứ ba.
 * Version: 1.0
 * Author: Bạn
 */

if (!defined('ABSPATH')) exit;

define('WC_API_SYNC_PATH', plugin_dir_path(__FILE__));

require_once WC_API_SYNC_PATH . 'includes/class-api-client.php';
require_once WC_API_SYNC_PATH . 'includes/class-sync-logger.php';
require_once WC_API_SYNC_PATH . 'includes/class-product-sync.php';

// Kích hoạt cron job khi plugin kích hoạt
register_activation_hook(__FILE__, function () {
    if (!wp_next_scheduled('wc_api_sync_cron')) {
        wp_schedule_event(time(), 'hourly', 'wc_api_sync_cron');
    }
});

// Hủy cron khi plugin bị vô hiệu hóa
register_deactivation_hook(__FILE__, function () {
    wp_clear_scheduled_hook('wc_api_sync_cron');
});

// Gán cron event với hàm đồng bộ
add_action('wc_api_sync_cron', function () {
    $sync = new Product_Sync();
    $sync->sync_from_api(); // Lấy từ API → WooCommerce
    $sync->sync_to_api();   // Gửi WooCommerce → API
});