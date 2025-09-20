<?php
/*
Plugin Name: Login Attempt Limiter
Description: Giới hạn số lần đăng nhập sai, khóa tài khoản tạm thời sau 5 lần.
Version: 1.0
Author: ChatGPT
*/

if ( ! defined( 'ABSPATH' ) ) exit;

class Login_Attempt_Limiter {
    private $max_attempts = 5;
    private $lock_duration; // phút
    private $option_name = 'lal_lock_duration';

    public function __construct() {
        $this->lock_duration = (int) get_option($this->option_name, 15); // default 15 minutes

        add_action('wp_login_failed', [$this, 'handle_login_failed']);
        add_filter('authenticate', [$this, 'check_if_locked'], 30, 3);
        add_action('wp_login', [$this, 'clear_login_attempts'], 10, 2);

        // Admin menu
        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    // Khi login thất bại
    public function handle_login_failed($username) {
        if (!$username) return;

        $user = get_user_by('login', $username);
        $now = time();

        if ($user) {
            // User tồn tại -> dùng user meta
            $attempts = (int) get_user_meta($user->ID, '_lal_failed_attempts', true);
            $locked_until = (int) get_user_meta($user->ID, '_lal_locked_until', true);

            if ($locked_until > $now) {
                // Đang bị khóa, không tăng thêm
                return;
            }

            $attempts++;
            update_user_meta($user->ID, '_lal_failed_attempts', $attempts);

            if ($attempts >= $this->max_attempts) {
                $locked_until = $now + $this->lock_duration * 60;
                update_user_meta($user->ID, '_lal_locked_until', $locked_until);
            }
        } else {
            // User không tồn tại -> lưu option với key riêng
            $option_key = 'lal_failed_attempts_' . md5(strtolower($username));
            $locked_key = 'lal_locked_until_' . md5(strtolower($username));

            $attempts = (int) get_option($option_key, 0);
            $locked_until = (int) get_option($locked_key, 0);

            if ($locked_until > $now) {
                return;
            }

            $attempts++;
            update_option($option_key, $attempts);

            if ($attempts >= $this->max_attempts) {
                $locked_until = $now + $this->lock_duration * 60;
                update_option($locked_key, $locked_until);
            }
        }
    }

    // Kiểm tra khóa khi authenticate
    public function check_if_locked($user, $username, $password) {
        if (empty($username)) return $user;

        $now = time();

        $wp_user = get_user_by('login', $username);

        if ($wp_user) {
            $locked_until = (int) get_user_meta($wp_user->ID, '_lal_locked_until', true);

            if ($locked_until > $now) {
                $remaining = ceil(($locked_until - $now) / 60);
                return new WP_Error('lal_account_locked', 
                    sprintf(__('Tài khoản bị khóa do đăng nhập sai quá nhiều lần. Vui lòng thử lại sau %d phút.', 'login-attempt-limiter'), $remaining)
                );
            }
        } else {
            $locked_key = 'lal_locked_until_' . md5(strtolower($username));
            $locked_until = (int) get_option($locked_key, 0);

            if ($locked_until > $now) {
                $remaining = ceil(($locked_until - $now) / 60);
                return new WP_Error('lal_account_locked', 
                    sprintf(__('Tài khoản bị khóa do đăng nhập sai quá nhiều lần. Vui lòng thử lại sau %d phút.', 'login-attempt-limiter'), $remaining)
                );
            }
        }

        return $user;
    }

    // Clear khi đăng nhập thành công
    public function clear_login_attempts($user_login, $user) {
        if (!$user) return;

        delete_user_meta($user->ID, '_lal_failed_attempts');
        delete_user_meta($user->ID, '_lal_locked_until');

        // Nếu có option lưu cho username này thì xóa luôn
        $option_key = 'lal_failed_attempts_' . md5(strtolower($user_login));
        $locked_key = 'lal_locked_until_' . md5(strtolower($user_login));

        delete_option($option_key);
        delete_option($locked_key);
    }

    // Admin menu
    public function admin_menu() {
        add_options_page(
            __('Login Attempt Limiter', 'login-attempt-limiter'),
            __('Login Attempt Limiter', 'login-attempt-limiter'),
            'manage_options',
            'login-attempt-limiter',
            [$this, 'settings_page']
        );
    }

    public function register_settings() {
        register_setting('lal_settings_group', $this->option_name, [
            'type' => 'integer',
            'sanitize_callback' => 'absint',
            'default' => 15,
        ]);
    }

    public function settings_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Login Attempt Limiter Settings', 'login-attempt-limiter'); ?></h1>
            <form method="post" action="options.php">
                <?php settings_fields('lal_settings_group'); ?>
                <?php do_settings_sections('lal_settings_group'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php _e('Lock Duration (minutes)', 'login-attempt-limiter'); ?></th>
                        <td>
                            <input type="number" name="<?php echo esc_attr($this->option_name); ?>" value="<?php echo esc_attr($this->lock_duration); ?>" min="1" max="1440" />
                            <p class="description"><?php _e('Thời gian khóa tài khoản sau khi đăng nhập sai vượt quá giới hạn.', 'login-attempt-limiter'); ?></p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}

new Login_Attempt_Limiter();
