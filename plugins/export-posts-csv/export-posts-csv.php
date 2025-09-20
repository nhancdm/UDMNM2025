<?php
/**
 * Plugin Name: Export Posts CSV
 * Description: Thêm nút xuất CSV danh sách bài viết trong admin và xuất file CSV tiêu đề, tác giả, ngày đăng.
 * Version: 1.0
 * Author: Bạn
 */

// Thêm nút "Export CSV" trên trang quản lý bài viết (posts list table)
function epcsv_add_export_button() {
    $screen = get_current_screen();
    if ($screen->id !== 'edit-post') return;

    $export_url = admin_url('admin-post.php?action=export_posts_csv&nonce=' . wp_create_nonce('export_posts_csv_nonce'));
    ?>
    <a href="<?php echo esc_url($export_url); ?>" class="page-title-action">Export CSV</a>
    <?php
}
add_action('manage_posts_extra_tablenav', 'epcsv_add_export_button', 20);

// Xử lý xuất file CSV khi click nút
function epcsv_handle_export() {
    if (!current_user_can('manage_options')) {
        wp_die('Bạn không có quyền truy cập.');
    }

    if (!isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'export_posts_csv_nonce')) {
        wp_die('Yêu cầu không hợp lệ.');
    }

    // Thiết lập header để trình duyệt tải file CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=posts-export-' . date('Y-m-d') . '.csv');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Mở output stream
    $output = fopen('php://output', 'w');

    // Ghi dòng tiêu đề
    fputcsv($output, ['ID', 'Tiêu đề', 'Tác giả', 'Ngày đăng']);

    // Lấy bài viết - có thể tối ưu nếu quá nhiều bài
    $args = [
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'fields'         => 'ids',
    ];
    $query = new WP_Query($args);

    foreach ($query->posts as $post_id) {
        $post = get_post($post_id);
        $author_name = get_the_author_meta('display_name', $post->post_author);
        $date = get_the_date('Y-m-d H:i:s', $post);

        // Ghi từng dòng dữ liệu
        fputcsv($output, [
            $post->ID,
            $post->post_title,
            $author_name,
            $date,
        ]);
    }

    fclose($output);
    exit;
}
add_action('admin_post_export_posts_csv', 'epcsv_handle_export');
