<?php
/**
 * Plugin Name: Admin IP Blocker
 * Description: Chặn các IP cụ thể không được phép truy cập vào khu vực quản trị (wp-admin).
 * Version: 1.0
 * Author: Bạn
 */

// 1. Giao diện cài đặt IP bị chặn
function aipb_register_settings() {
    add_option('aipb_blocked_ips', '');
    register_setting('aipb_options_group', 'aipb_blocked_ips');
}

add_action('admin_init', 'aipb_register_settings');

function aipb_settings_page() {
    ?>
    <div class="wrap">
        <h1>Chặn IP truy cập quản trị</h1>
        <form method="post" action="options.php">
            <?php settings_fields('aipb_options_group'); ?>
            <label for="aipb_blocked_ips">Nhập danh sách IP, mỗi IP một dòng:</label><br>
            <textarea id="aipb_blocked_ips" name="aipb_blocked_ips" rows="10" cols="50"><?php echo esc_textarea(get_option('aipb_blocked_ips')); ?></textarea>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function aipb_add_settings_menu() {
    add_options_page('Chặn IP Quản Trị', 'Chặn IP Quản Trị', 'manage_options', 'aipb', 'aipb_settings_page');
}

add_action('admin_menu', 'aipb_add_settings_menu');


// 2. Kiểm tra IP khi truy cập admin
function aipb_block_ips_on_admin() {
    if (!is_admin()) return;

    $blocked_ips_raw = get_option('aipb_blocked_ips');
    if (!$blocked_ips_raw) return;

    $blocked_ips = array_map('trim', explode("\n", $blocked_ips_raw));
    $user_ip = $_SERVER['REMOTE_ADDR'] ?? '';

    // Kiểm tra nếu IP bị chặn
    if (in_array($user_ip, $blocked_ips)) {
        wp_die(__('Bạn không được phép truy cập khu vực quản trị. IP của bạn đã bị chặn.', 'aipb'), 'Truy cập bị từ chối', array('response' => 403));
    }
}
add_action('admin_init', 'aipb_block_ips_on_admin');
