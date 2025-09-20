<?php

class Rating_Handler {
    public static function display_form() {
        global $post;
        $avg = self::get_average_rating($post->ID);

        echo '<div class="mcpr-rating-box">';
        echo '<h3>Đánh giá bài viết</h3>';
        echo '<form id="mcpr-form" data-post-id="' . esc_attr($post->ID) . '">
            <label>Nội dung: <input type="number" name="content" min="1" max="5" required></label><br>
            <label>Hình ảnh: <input type="number" name="image" min="1" max="5" required></label><br>
            <label>Tính hữu ích: <input type="number" name="helpful" min="1" max="5" required></label><br>
            <button type="submit">Gửi đánh giá</button>
        </form>';
        echo '<div id="mcpr-message"></div>';
        echo "<p>Điểm trung bình: <strong>{$avg}</strong>/5</p>";
        echo '</div>';
    }

    public static function submit_rating() {
        check_ajax_referer('mcpr_nonce', 'nonce');

        global $wpdb;
        $post_id = intval($_POST['post_id']);
        $content = intval($_POST['content']);
        $image = intval($_POST['image']);
        $helpful = intval($_POST['helpful']);
        $ip = $_SERVER['REMOTE_ADDR'];
        $table = $wpdb->prefix . 'post_ratings';

        // Ngăn spam: 1 IP chỉ đánh giá 1 bài trong 30 phút
        $recent = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE post_id = %d AND ip_address = %s AND created_at >= NOW() - INTERVAL 30 MINUTE",
            $post_id, $ip
        ));

        if ($recent > 0) {
            wp_send_json_error(['msg' => 'Bạn đã đánh giá bài viết này gần đây. Vui lòng thử lại sau.']);
        }

        $wpdb->insert($table, [
            'post_id' => $post_id,
            'ip_address' => $ip,
            'content_rating' => $content,
            'image_rating' => $image,
            'helpful_rating' => $helpful,
        ]);

        wp_send_json_success(['msg' => 'Cảm ơn bạn đã đánh giá!']);
    }

    public static function get_average_rating($post_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'post_ratings';

        $avg = $wpdb->get_row($wpdb->prepare("
            SELECT AVG((content_rating + image_rating + helpful_rating)/3) as avg_rating
            FROM $table WHERE post_id = %d
        ", $post_id));

        return $avg ? round($avg->avg_rating, 2) : 'Chưa có đánh giá';
    }
}