<?php
/*
Plugin Name: xAI Chatbot
Description: A WordPress plugin to integrate an AI chatbot using xAI API with customizable scripts.
Version: 1.0
Author: Grok
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('XAI_CHATBOT_PATH', plugin_dir_path(__FILE__));
define('XAI_CHATBOT_URL', plugin_dir_url(__FILE__));

// Enqueue frontend scripts and styles
add_action('wp_enqueue_scripts', 'xai_chatbot_enqueue_scripts');
function xai_chatbot_enqueue_scripts() {
    wp_enqueue_style('xai-chatbot-style', XAI_CHATBOT_URL . 'assets/css/chatbot.css', [], '1.0');
    wp_enqueue_script('xai-chatbot-script', XAI_CHATBOT_URL . 'assets/js/chatbot.js', ['jquery'], '1.0', true);
    
    // Localize script with API endpoint and settings
    $settings = get_option('xai_chatbot_settings', []);
    wp_localize_script('xai-chatbot-script', 'xaiChatbot', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('xai_chatbot_nonce'),
        'welcome_message' => !empty($settings['welcome_message']) ? $settings['welcome_message'] : 'Hello! How can I assist you today?',
    ]);
}

// Register admin menu
add_action('admin_menu', 'xai_chatbot_admin_menu');
function xai_chatbot_admin_menu() {
    add_menu_page(
        'xAI Chatbot Settings',
        'xAI Chatbot',
        'manage_options',
        'xai-chatbot',
        'xai_chatbot_admin_page',
        'dashicons-format-chat',
        80
    );
}

// Admin page callback
function xai_chatbot_admin_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Save settings
    if (isset($_POST['xai_chatbot_save'])) {
        check_admin_referer('xai_chatbot_settings');
        $settings = [
            'api_key' => sanitize_text_field($_POST['xai_api_key']),
            'welcome_message' => sanitize_text_field($_POST['welcome_message']),
            'scripts' => wp_kses_post_deep($_POST['chatbot_scripts']),
        ];
        update_option('xai_chatbot_settings', $settings);
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }

    $settings = get_option('xai_chatbot_settings', []);
    ?>
<div class="wrap">
    <h1>xAI Chatbot Settings</h1>
    <form method="post">
        <?php wp_nonce_field('xai_chatbot_settings'); ?>
        <table class="form-table">
            <tr>
                <th><label for="xai_api_key">xAI API Key</label></th>
                <td><input type="text" name="xai_api_key" id="xai_api_key"
                        value="<?php echo esc_attr($settings['api_key'] ?? ''); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="welcome_message">Welcome Message</label></th>
                <td><input type="text" name="welcome_message" id="welcome_message"
                        value="<?php echo esc_attr($settings['welcome_message'] ?? ''); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="chatbot_scripts">Chatbot Scripts</label></th>
                <td><textarea name="chatbot_scripts" id="chatbot_scripts" rows="10"
                        class="large-text"><?php echo esc_textarea($settings['scripts'] ?? ''); ?></textarea></td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" name="xai_chatbot_save" class="button-primary" value="Save Changes">
        </p>
    </form>
</div>
<?php
}

// AJAX handler for chatbot messages
add_action('wp_ajax_xai_chatbot_message', 'xai_chatbot_handle_message');
add_action('wp_ajax_nopriv_xai_chatbot_message', 'xai_chatbot_handle_message');
function xai_chatbot_handle_message() {
    check_ajax_referer('xai_chatbot_nonce', 'nonce');

    $message = sanitize_text_field($_POST['message']);
    $settings = get_option('xai_chatbot_settings', []);
    $api_key = $settings['api_key'] ?? '';

    if (empty($api_key) || empty($message)) {
        wp_send_json_error(['message' => 'Invalid request.']);
    }

    // Call xAI API (simplified example)
    $response = wp_remote_post('https://api.x.ai/v1/chat/completions', [
        'headers' => [
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type' => 'application/json',
        ],
        'body' => json_encode([
            'model' => 'grok-3',
            'messages' => [
                ['role' => 'user', 'content' => $message],
            ],
        ]),
        'timeout' => 10,
    ]);

    if (is_wp_error($response)) {
        wp_send_json_error(['message' => 'API error.']);
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    $reply = $data['choices'][0]['message']['content'] ?? 'Sorry, I could not process your request.';

    wp_send_json_success(['message' => $reply]);
}

// Add chatbot HTML to footer
add_action('wp_footer', 'xai_chatbot_add_html');
function xai_chatbot_add_html() {
    ?>
<div id="xai-chatbot" class="xai-chatbot">
    <div class="chatbot-header">xAI Chatbot</div>
    <div class="chatbot-messages"></div>
    <div class="chatbot-input">
        <input type="text" placeholder="Type your message...">
        <button>Send</button>
    </div>
</div>
<?php
}
?>