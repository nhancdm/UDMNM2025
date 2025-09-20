<?php
/**
 * Plugin Name: Portfolio Custom Post Type
 * Description: Tạo custom post type "Portfolio" và thêm meta box để nhập thông tin khách hàng, ngày hoàn thành.
 * Version: 1.0
 * Author: Bạn
 */

// 1. Đăng ký Custom Post Type: Portfolio
function pcp_register_portfolio_post_type() {
    $labels = array(
        'name'               => 'Portfolio',
        'singular_name'      => 'Portfolio',
        'add_new'            => 'Thêm dự án',
        'add_new_item'       => 'Thêm dự án mới',
        'edit_item'          => 'Sửa dự án',
        'new_item'           => 'Dự án mới',
        'all_items'          => 'Tất cả dự án',
        'view_item'          => 'Xem dự án',
        'search_items'       => 'Tìm dự án',
        'not_found'          => 'Không tìm thấy',
        'menu_name'          => 'Portfolio',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'menu_icon'          => 'dashicons-portfolio',
        'supports'           => array('title', 'editor', 'thumbnail'),
        'has_archive'        => true,
        'rewrite'            => array('slug' => 'portfolio'),
        'show_in_rest'       => true,
    );

    register_post_type('portfolio', $args);
}
add_action('init', 'pcp_register_portfolio_post_type');


// 2. Thêm Meta Box
function pcp_add_portfolio_meta_boxes() {
    add_meta_box(
        'pcp_portfolio_details',
        'Thông tin dự án',
        'pcp_render_meta_box',
        'portfolio',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'pcp_add_portfolio_meta_boxes');

function pcp_render_meta_box($post) {
    $client = get_post_meta($post->ID, '_pcp_client', true);
    $completed_date = get_post_meta($post->ID, '_pcp_completed_date', true);

    wp_nonce_field('pcp_save_meta_box_data', 'pcp_meta_box_nonce');

    echo '<p><label>Khách hàng:</label><br />';
    echo '<input type="text" name="pcp_client" value="' . esc_attr($client) . '" size="30" /></p>';

    echo '<p><label>Ngày hoàn thành:</label><br />';
    echo '<input type="date" name="pcp_completed_date" value="' . esc_attr($completed_date) . '" /></p>';
}


// 3. Lưu Meta Box khi lưu bài viết
function pcp_save_meta_box_data($post_id) {
    if (!isset($_POST['pcp_meta_box_nonce']) || !wp_verify_nonce($_POST['pcp_meta_box_nonce'], 'pcp_save_meta_box_data')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['pcp_client'])) {
        update_post_meta($post_id, '_pcp_client', sanitize_text_field($_POST['pcp_client']));
    }

    if (isset($_POST['pcp_completed_date'])) {
        update_post_meta($post_id, '_pcp_completed_date', sanitize_text_field($_POST['pcp_completed_date']));
    }
}
add_action('save_post', 'pcp_save_meta_box_data');
