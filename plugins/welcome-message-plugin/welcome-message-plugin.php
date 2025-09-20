<?php
/**
 * Plugin Name: Welcome Message Plugin
 * Description: Hiển thị thông báo chào mừng tùy chỉnh trong admin dashboard.
 * Version: 1.0
 * Author: Bạn
 */

// --- 1. Thêm mục trong menu Cài đặt ---
add_action('admin_menu', function() {
    add_options_page(
        'Welcome Message Settings',
        'Welcome Message',
        'manage_options',
        'welcome-message-plugin',
        'wmp_settings_page'
    );
});

// --- 2. Đăng ký setting với Settings API ---
add_action('admin_init', function() {
    register_setting('wmp_settings_group', 'wmp_custom_message');
});

// --- 3. Trang Settings ---
function wmp_settings_page() {
    ?>
    <div class="wrap">
        <h1>Welcome Message Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('wmp_settings_group'); ?>
            <?php do_settings_sections('wmp_settings_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Thông báo chào mừng:</th>
                    <td>
                        <input type="text" name="wmp_custom_message" value="<?php echo esc_attr(get_option('wmp_custom_message', 'Chào mừng bạn đến với trang quản trị!')); ?>" style="width: 400px;" />
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// --- 4. Hiển thị thông báo khi vào admin dashboard ---
add_action('admin_notices', function() {
    $message = get_option('wmp_custom_message', '');
    if (!empty($message)) {
        echo '<div class="notice notice-success is-dismissible">';
        echo '<p><strong>' . esc_html($message) . '</strong></p>';
        echo '</div>';
    }
});
