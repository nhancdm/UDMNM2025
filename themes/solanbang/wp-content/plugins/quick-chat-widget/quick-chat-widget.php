<?php
/*
Plugin Name: Quick Chat Widget
Description: Widget quick chat tích hợp gọi điện, WhatsApp, Zalo cho Elementor.
Version: 1.0
Author: Your Name
*/

// Ngăn truy cập trực tiếp
if (!defined('ABSPATH')) {
    exit;
}

// Kiểm tra Elementor
function quick_chat_widget_check() {
    if (!did_action('elementor/loaded')) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>Vui lòng cài đặt và kích hoạt Elementor để sử dụng plugin này.</p></div>';
        });
        return;
    }
}
add_action('plugins_loaded', 'quick_chat_widget_check');

// Bao gồm file widget
require_once plugin_dir_path(__FILE__) . 'widget.php';

function quick_chat_widget_enqueue_assets() {
    // Nạp Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
    // Nạp CSS tùy chỉnh
    wp_enqueue_style('quick-chat-style', plugins_url('/assets/css/style.css', __FILE__));


    // Nạp JS tùy chỉnh
    wp_enqueue_script('quick-chat-script', plugins_url('/assets/js/script.js', __FILE__), ['jquery'], null, true);
}
add_action('wp_enqueue_scripts', 'quick_chat_widget_enqueue_assets');
add_action('elementor/frontend/after_enqueue_styles', 'quick_chat_widget_enqueue_assets');