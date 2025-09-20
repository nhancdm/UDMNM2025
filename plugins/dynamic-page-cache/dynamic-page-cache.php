<?php
/**
 * Plugin Name: Dynamic Page Cache
 * Description: Lưu cache HTML tĩnh cho trang/post để tăng tốc độ tải trang.
 * Version: 1.0
 * Author: Bạn
 */

if (!defined('ABSPATH')) exit;

define('DPC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DPC_CACHE_DIR', WP_CONTENT_DIR . '/dpc-cache/');

require_once DPC_PLUGIN_DIR . 'includes/class-cache-manager.php';
require_once DPC_PLUGIN_DIR . 'includes/class-hooks.php';
require_once DPC_PLUGIN_DIR . 'includes/class-settings.php';

register_activation_hook(__FILE__, function () {
    if (!file_exists(DPC_CACHE_DIR)) {
        mkdir(DPC_CACHE_DIR, 0755, true);
    }
});

add_action('init', ['DPC_Hooks', 'init_hooks']);