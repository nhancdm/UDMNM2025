<?php
/**
 * Plugin Name: Multi Criteria Post Rating
 * Description: Đánh giá bài viết theo nhiều tiêu chí và lưu vào bảng riêng.
 * Version: 1.0
 * Author: Bạn
 */

if (!defined('ABSPATH')) exit;

define('MCPR_PATH', plugin_dir_path(__FILE__));

require_once MCPR_PATH . 'includes/class-rating-db.php';
require_once MCPR_PATH . 'includes/class-rating-handler.php';

register_activation_hook(__FILE__, ['Rating_DB', 'create_table']);

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('mcpr-css', plugins_url('assets/rating.css', __FILE__));
    wp_enqueue_script('mcpr-js', plugins_url('assets/rating.js', __FILE__), ['jquery'], null, true);
    wp_localize_script('mcpr-js', 'mcpr_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('mcpr_nonce'),
    ]);
});

// AJAX handlers
add_action('wp_ajax_nopriv_mcpr_submit', ['Rating_Handler', 'submit_rating']);
add_action('wp_ajax_mcpr_submit', ['Rating_Handler', 'submit_rating']);

// Hiển thị giao diện
add_filter('the_content', function ($content) {
    if (is_single()) {
        ob_start();
        Rating_Handler::display_form();
        $form = ob_get_clean();
        return $content . $form;
    }
    return $content;
});