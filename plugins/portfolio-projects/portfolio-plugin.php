<?php
/*
Plugin Name: Portfolio Manager
Description: Tạo custom post type cho Portfolio và hiển thị giao diện tùy chỉnh.
Version: 1.0
Author: AI
*/

// Đăng ký post type
function pm_register_portfolio_post_type() {
    register_post_type('portfolio', [
        'labels' => [
            'name' => 'Portfolio',
            'singular_name' => 'Dự án',
            'add_new_item' => 'Thêm dự án mới',
            'edit_item' => 'Sửa dự án',
            'new_item' => 'Dự án mới',
            'view_item' => 'Xem dự án',
            'all_items' => 'Tất cả dự án',
        ],
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'portfolio'],
        'supports' => ['title', 'editor', 'thumbnail'],
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-portfolio',
    ]);
}
add_action('init', 'pm_register_portfolio_post_type');

// Meta box
function pm_add_portfolio_meta_box() {
    add_meta_box(
        'pm_portfolio_details',
        'Thông tin dự án',
        'pm_render_portfolio_meta_box',
        'portfolio',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'pm_add_portfolio_meta_box');

function pm_render_portfolio_meta_box($post) {
    $client = get_post_meta($post->ID, '_pm_client', true);
    $completed = get_post_meta($post->ID, '_pm_completed_date', true);
    wp_nonce_field('pm_save_portfolio_meta', 'pm_portfolio_nonce');
    ?>
    <p>
        <label for="pm_client">Khách hàng:</label><br>
        <input type="text" id="pm_client" name="pm_client" value="<?= esc_attr($client) ?>" style="width:100%;">
    </p>
    <p>
        <label for="pm_completed_date">Ngày hoàn thành:</label><br>
        <input type="date" id="pm_completed_date" name="pm_completed_date" value="<?= esc_attr($completed) ?>">
    </p>
    <?php
}

function pm_save_portfolio_meta($post_id) {
    if (!isset($_POST['pm_portfolio_nonce']) || !wp_verify_nonce($_POST['pm_portfolio_nonce'], 'pm_save_portfolio_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    update_post_meta($post_id, '_pm_client', sanitize_text_field($_POST['pm_client']));
    update_post_meta($post_id, '_pm_completed_date', sanitize_text_field($_POST['pm_completed_date']));
}
add_action('save_post_portfolio', 'pm_save_portfolio_meta');

// Kiểm tra slug trùng
add_action('save_post_portfolio', 'pm_check_duplicate_slug', 10, 3);
function pm_check_duplicate_slug($post_id, $post, $update) {
    if (wp_is_post_revision($post_id)) return;

    $slug = $post->post_name;
    $args = [
        'post_type' => 'portfolio',
        'post_status' => 'any',
        'post__not_in' => [$post_id],
        'name' => $slug
    ];
    $existing = get_posts($args);
    if (!empty($existing)) {
        wp_die('Slug này đã tồn tại. Vui lòng đổi tên dự án để tạo slug khác.');
    }
}
