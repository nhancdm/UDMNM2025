<?php
/**
 * Plugin Name: Post View Counter
 * Description: Plugin đơn giản để đếm và hiển thị số lượt xem bài viết mà không cần plugin bên thứ ba.
 * Version: 1.0
 * Author: Bạn
 */

// 1. Hàm tăng lượt xem bài viết
function pvc_increase_post_views($post_id) {
    if (!is_single() || is_admin()) return;

    $views = (int) get_post_meta($post_id, 'pvc_post_views', true);
    $views++;
    update_post_meta($post_id, 'pvc_post_views', $views);
}

// 2. Gọi hàm tăng lượt xem mỗi khi load bài viết
add_action('wp_head', function() {
    if (is_single()) {
        global $post;
        if ($post && $post instanceof WP_Post) {
            pvc_increase_post_views($post->ID);
        }
    }
});

// 3. Hiển thị số lượt xem ở cuối bài viết
add_filter('the_content', function($content) {
    if (is_single()) {
        global $post;
        $views = (int) get_post_meta($post->ID, 'pvc_post_views', true);
        $views_text = "<p><strong>Lượt xem: {$views}</strong></p>";
        return $content . $views_text;
    }
    return $content;
});

// 4. Thêm cột "Lượt xem" vào danh sách bài viết trong admin
add_filter('manage_posts_columns', function($columns) {
    $columns['pvc_post_views'] = 'Lượt xem';
    return $columns;
});

add_action('manage_posts_custom_column', function($column, $post_id) {
    if ($column === 'pvc_post_views') {
        echo (int) get_post_meta($post_id, 'pvc_post_views', true);
    }
}, 10, 2);
