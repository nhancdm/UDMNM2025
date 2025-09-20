<?php
/**
 * Plugin Name: Limit Failed Logins
 * Description: Khóa tạm thời tài khoản sau 5 lần đăng nhập sai. Có thể cấu hình thời gian khóa.
 * Version: 1.0
 * Author: Bạn
 */

define('LFL_MAX_RETRIES', 5);
define('LFL_LOCKOUT_DURATION', 15 * MINUTE_IN_SECONDS); // mặc định 15 phút

// Ghi lại khi đăng nhập sai
function lfl_on_login_failed($username) {
    $user = get_user_by('login', $username);
    if (!$user) return;

    $user_id = $user->ID;
    $lock_until = get_user_meta($user_id, 'lfl_lock_until', true);

    if ($lock_until && time() < $lock_until) {
        // Đã bị khóa => không tăng tiếp
        return;
    }

    $attempts = (int) get_user_meta($user_id, 'lfl_failed_attempts', true);
    $attempts++;

    if ($attempts >= LFL_MAX_RETRIES) {
        // Khóa tài khoản
        update_user_meta($user_id, 'lfl_lock_until', time() + LFL_LOCKOUT_DURATION);
        delete_user_meta($user_id, 'lfl_failed_attempts');
    } else {
        update_user_meta($user_id, 'lfl_failed_attempts', $attempts);
    }
}
add_action('wp_login_failed', 'lfl_on_login_failed');

// Chặn đăng nhập nếu đang bị khóa
function lfl_authenticate_user($user, $username, $password) {
    if (is_wp_error($user)) return $user;

    $lock_until = get_user_meta($user->ID, 'lfl_lock_until', true);
    if ($lock_until && time() < $lock_until) {
        $remaining = $lock_until - time();
        return new WP_Error('account_locked', 'Tài khoản của bạn đã bị khóa tạm thời. Vui lòng thử lại sau ' . ceil($remaining / 60) . ' phút.');
    }

    return $user;
}
add_filter('authenticate', 'lfl_authenticate_user', 30, 3);

// Xóa dữ liệu khi đăng nhập thành công
function lfl_clear_failed_attempts($user_login, $user) {
    delete_user_meta($user->ID, 'lfl_failed_attempts');
    delete_user_meta($user->ID, 'lfl_lock_until');
}
add_action('wp_login', 'lfl_clear_failed_attempts', 10, 2);
