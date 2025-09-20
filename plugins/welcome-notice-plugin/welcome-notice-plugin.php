<?php
/**
 * Plugin Name: Welcome Notice Plugin
 * Description: Hiển thị thông báo chào mừng trong Dashboard, nội dung có thể tùy chỉnh qua Settings API.
 * Version: 1.0
 * Author: AI Developer
 */

// Đăng ký menu Settings
add_action('admin_menu', 'wnp_add_settings_menu');
function wnp_add_settings_menu() {
    add_options_page(
        'Welcome Notice Settings',
        'Welcome Notice',
        'manage_options',
        'wnp-settings',
        'wnp_settings_page_html'
    );
}

// Đăng ký cài đặt
add_action('admin_init', 'wnp_register_settings');
function wnp_register_settings() {
    register_setting('wnp_settings_group', 'wnp_notice_message');

    add_settings_section(
        'wnp_settings_section',
        'Tùy chỉnh thông báo chào mừng',
        null,
        'wnp-settings'
    );

    add_settings_field(
        'wnp_notice_message',
        'Nội dung thông báo',
        'wnp_notice_message_field_html',
        'wnp-settings',
        'wnp_settings_section'
    );
}

function wnp_notice_message_field_html() {
    $message = esc_attr(get_option('wnp_notice_message', 'Chào mừng bạn đến với trang quản trị!'));
    echo "<textarea name='wnp_notice_message' rows='4' cols='50'>$message</textarea>";
}

function wnp_settings_page_html() {
    ?>
    <div class="wrap">
        <h1>Welcome Notice Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('wnp_settings_group');
            do_settings_sections('wnp-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Hook hiển thị thông báo
add_action('admin_notices', 'wnp_display_admin_notice');
function wnp_display_admin_notice() {
    if (!current_user_can('manage_options')) return;

    $message = get_option('wnp_notice_message', 'Chào mừng bạn đến với trang quản trị!');
    echo "<div class='notice notice-success is-dismissible'><p>" . esc_html($message) . "</p></div>";
}
