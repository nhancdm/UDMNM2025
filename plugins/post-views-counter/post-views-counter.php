<?php
/**
 * Plugin Name: Post Views Counter (Simple)
 * Description: Đếm và hiển thị số lượt xem bài viết mà không cần plugin bên thứ ba.
 * Version: 1.0
 * Author: AI Developer
 */

// Tăng số lượt xem khi load bài viết (chỉ frontend)
add_action('wp_head', 'pvc_increase_post_views');
function pvc_increase_post_views() {
    if (is_singular('post')) {
        global $post;

        $post_id = $post->ID;
        $views = get_post_meta($post_id, 'post_views_count', true);

        $views = $views ? intval($views) + 1 : 1;
        update_post_meta($post_id, 'post_views_count', $views);
    }
}

// Hiển thị số lượt xem ở cuối bài viết
add_filter('the_content', 'pvc_append_views_to_content');
function pvc_append_views_to_content($content) {
    if (is_singular('post')) {
        global $post;
        $views = get_post_meta($post->ID, 'post_views_count', true);
        $views = $views ? intval($views) : 0;

        $views_html = "<p><strong>Lượt xem:</strong> " . number_format($views) . "</p>";
        return $content . $views_html;
    }
    return $content;
}

// Tùy chọn: Thêm cột "Lượt xem" trong admin post list
add_filter('manage_post_posts_columns', 'pvc_add_views_column');
function pvc_add_views_column($columns) {
    $columns['post_views'] = 'Lượt xem';
    return $columns;
}

add_action('manage_post_posts_custom_column', 'pvc_show_views_column', 10, 2);
function pvc_show_views_column($column, $post_id) {
    if ($column === 'post_views') {
        $views = get_post_meta($post_id, 'post_views_count', true);
        echo number_format($views ? $views : 0);
    }
}
