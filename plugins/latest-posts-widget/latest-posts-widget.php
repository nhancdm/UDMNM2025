<?php
/**
 * Plugin Name: Latest Posts Widget
 * Description: Thêm widget hiển thị danh sách bài viết mới nhất trong sidebar, có thể tùy chỉnh tiêu đề và số lượng bài viết.
 * Version: 1.0
 * Author: AI Developer
 */

// Đăng ký widget
add_action('widgets_init', function() {
    register_widget('Latest_Posts_Widget');
});

// Định nghĩa class widget
class Latest_Posts_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'latest_posts_widget',
            'Latest Posts (AI)',
            ['description' => 'Hiển thị các bài viết mới nhất']
        );
    }

    // Hiển thị widget ở frontend
    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title'] ?? 'Bài viết mới');
        $count = !empty($instance['count']) ? absint($instance['count']) : 5;

        echo $args['before_widget'];
        if (!empty($title)) {
            echo $args['before_title'] . esc_html($title) . $args['after_title'];
        }

        $query = new WP_Query([
            'posts_per_page' => $count,
            'post_status' => 'publish',
        ]);

        if ($query->have_posts()) {
            echo '<ul>';
            while ($query->have_posts()) {
                $query->the_post();
                echo '<li><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></li>';
            }
            echo '</ul>';
            wp_reset_postdata();
        } else {
            echo '<p>Không có bài viết.</p>';
        }

        echo $args['after_widget'];
    }

    // Form backend trong admin
    public function form($instance) {
        $title = $instance['title'] ?? 'Bài viết mới';
        $count = $instance['count'] ?? 5;
        ?>
        <p>
            <label for="<?= esc_attr($this->get_field_id('title')); ?>">Tiêu đề:</label>
            <input class="widefat" id="<?= esc_attr($this->get_field_id('title')); ?>"
                   name="<?= esc_attr($this->get_field_name('title')); ?>" type="text"
                   value="<?= esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?= esc_attr($this->get_field_id('count')); ?>">Số bài viết:</label>
            <input class="tiny-text" id="<?= esc_attr($this->get_field_id('count')); ?>"
                   name="<?= esc_attr($this->get_field_name('count')); ?>" type="number" step="1" min="1"
                   value="<?= esc_attr($count); ?>" size="3">
        </p>
        <?php
    }

    // Lưu dữ liệu widget
    public function update($new_instance, $old_instance) {
        return [
            'title' => sanitize_text_field($new_instance['title']),
            'count' => absint($new_instance['count']),
        ];
    }
}
