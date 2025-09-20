<?php
/**
 * Plugin Name: Custom Button Shortcode
 * Description: Cung cấp shortcode [custom_button] để tạo nút CTA với các thuộc tính tùy chỉnh.
 * Version: 1.0
 * Author: Bạn
 */

// Đăng ký shortcode
function cbs_register_button_shortcode($atts) {
    // Thiết lập giá trị mặc định
    $atts = shortcode_atts(array(
        'text' => 'Click here',
        'url' => '#',
        'color' => 'blue', // Hỗ trợ: blue, red, green, black, etc.
        'bg' => '#f0f0f0', // màu nền
        'hover' => '#ddd'  // màu hover
    ), $atts, 'custom_button');

    // Làm sạch dữ liệu đầu vào để tránh XSS
    $text = esc_html($atts['text']);
    $url = esc_url($atts['url']);
    $color = sanitize_hex_color($atts['color']) ?: $atts['color']; // cho phép tên màu hoặc hex
    $bg = sanitize_hex_color($atts['bg']) ?: '#f0f0f0';
    $hover = sanitize_hex_color($atts['hover']) ?: '#ddd';

    // Tạo class ngẫu nhiên để style riêng cho từng nút
    $unique_class = 'cbs-btn-' . uniqid();

    // Tạo CSS động
    $css = "
    <style>
    .{$unique_class} {
        display: inline-block;
        padding: 10px 20px;
        color: {$color};
        background-color: {$bg};
        text-decoration: none;
        border-radius: 5px;
        transition: background 0.3s;
    }
    .{$unique_class}:hover {
        background-color: {$hover};
    }
    </style>
    ";

    // Trả về HTML nút + CSS
    return $css . "<a href='{$url}' class='{$unique_class}'>{$text}</a>";
}
add_shortcode('custom_button', 'cbs_register_button_shortcode');
