<?php
/**
 * Plugin Name: OAuth Login for WordPress
 * Description: Cho phép người dùng đăng nhập bằng Google hoặc GitHub qua OAuth 2.0.
 * Version: 1.0
 * Author: Bạn
 */

if (!defined('ABSPATH')) exit;

define('OAUTH_PLUGIN_URL', plugin_dir_url(__FILE__));
define('OAUTH_REDIRECT_URI', site_url('/oauth-callback/'));

require_once plugin_dir_path(__FILE__) . 'includes/class-oauth-provider.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-helper.php';

register_activation_hook(__FILE__, ['OAuth_Handler', 'add_callback_rewrite']);
add_action('init', ['OAuth_Handler', 'listen_callback']);
add_shortcode('oauth_login_buttons', ['OAuth_Handler', 'render_login_buttons']);