<?php
if (!defined('ABSPATH')) {
    exit;
}

// Đăng ký widget
function register_quick_chat_widget($widgets_manager) {
    require_once(__DIR__ . '/widgets/quick-chat.php');
    $widgets_manager->register(new \Quick_Chat_Widget());
}
add_action('elementor/widgets/register', 'register_quick_chat_widget');

// Thêm danh mục tùy chỉnh
function add_quick_chat_categories($elements_manager) {
    $elements_manager->add_category(
        'quick-chat',
        [
            'title' => __('Quick Chat Widgets', 'quick-chat-widget'),
            'icon' => 'fa fa-comment',
        ]
    );
}
add_action('elementor/elements/categories_registered', 'add_quick_chat_categories');