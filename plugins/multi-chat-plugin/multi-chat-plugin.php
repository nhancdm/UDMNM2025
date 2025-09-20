<?php
/*
Plugin Name: Multi Chat Integration
Description: A WordPress plugin to integrate WhatsApp, KakaoTalk, Zalo, Facebook Messenger, and WeChat with SVG icon floating chat buttons.
Version: 1.1
Author: Your Name
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue Styles and Scripts
function mci_enqueue_assets() {
    wp_enqueue_style('mci-styles', plugins_url('./css/style.css', __FILE__));
    wp_enqueue_script('mci-script', plugins_url('./js/script.js', __FILE__), array('jquery'), '1.1', true);
}
add_action('wp_enqueue_scripts', 'mci_enqueue_assets');

// Add Admin Menu
function mci_add_admin_menu() {
    add_options_page(
        'Multi Chat Settings',
        'Chat Settings',
        'manage_options',
        'mci_settings',
        'mci_settings_page'
    );
}
add_action('admin_menu', 'mci_add_admin_menu');

// Register Settings
function mci_register_settings() {
    register_setting('mci_settings_group', 'mci_whatsapp_number');
    register_setting('mci_settings_group', 'mci_whatsapp_enabled');
    register_setting('mci_settings_group', 'mci_kakaotalk_id');
    register_setting('mci_settings_group', 'mci_kakaotalk_enabled');
    register_setting('mci_settings_group', 'mci_zalo_id');
    register_setting('mci_settings_group', 'mci_zalo_enabled');
    register_setting('mci_settings_group', 'mci_facebook_id');
    register_setting('mci_settings_group', 'mci_facebook_enabled');
    register_setting('mci_settings_group', 'mci_wechat_id');
    register_setting('mci_settings_group', 'mci_wechat_enabled');
}
add_action('admin_init', 'mci_register_settings');

// Settings Page HTML
function mci_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields('mci_settings_group'); ?>
            <?php do_settings_sections('mci_settings_group'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="mci_whatsapp_number"><?php _e('WhatsApp Number', 'multi-chat-plugin'); ?></label></th>
                    <td>
                        <input type="text" id="mci_whatsapp_number" name="mci_whatsapp_number" value="<?php echo esc_attr(get_option('mci_whatsapp_number')); ?>" placeholder="+1234567890" />
                        <label><input type="checkbox" name="mci_whatsapp_enabled" value="1" <?php checked(1, get_option('mci_whatsapp_enabled')); ?> /> <?php _e('Enable', 'multi-chat-plugin'); ?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="mci_kakaotalk_id"><?php _e('KakaoTalk ID', 'multi-chat-plugin'); ?></label></th>
                    <td>
                        <input type="text" id="mci_kakaotalk_id" name="mci_kakaotalk_id" value="<?php echo esc_attr(get_option('mci_kakaotalk_id')); ?>" />
                        <label><input type="checkbox" name="mci_kakaotalk_enabled" value="1" <?php checked(1, get_option('mci_kakaotalk_enabled')); ?> /> <?php _e('Enable', 'multi-chat-plugin'); ?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="mci_zalo_id"><?php _e('Zalo ID', 'multi-chat-plugin'); ?></label></th>
                    <td>
                        <input type="text" id="mci_zalo_id" name="mci_zalo_id" value="<?php echo esc_attr(get_option('mci_zalo_id')); ?>" />
                        <label><input type="checkbox" name="mci_zalo_enabled" value="1" <?php checked(1, get_option('mci_zalo_enabled')); ?> /> <?php _e('Enable', 'multi-chat-plugin'); ?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="mci_facebook_id"><?php _e('Facebook Page ID', 'multi-chat-plugin'); ?></label></th>
                    <td>
                        <input type="text" id="mci_facebook_id" name="mci_facebook_id" value="<?php echo esc_attr(get_option('mci_facebook_id')); ?>" />
                        <label><input type="checkbox" name="mci_facebook_enabled" value="1" <?php checked(1, get_option('mci_facebook_enabled')); ?> /> <?php _e('Enable', 'multi-chat-plugin'); ?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="mci_wechat_id"><?php _e('WeChat ID', 'multi-chat-plugin'); ?></label></th>
                    <td>
                        <input type="text" id="mci_wechat_id" name="mci_wechat_id" value="<?php echo esc_attr(get_option('mci_wechat_id')); ?>" />
                        <label><input type="checkbox" name="mci_wechat_enabled" value="1" <?php checked(1, get_option('mci_wechat_enabled')); ?> /> <?php _e('Enable', 'multi-chat-plugin'); ?></label>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Display Floating Chat Buttons with SVG Icons
function mci_display_chat_buttons() {
    $output = '<div class="mci-chat-container">';
    $output .= '<div class="mci-chat-toggle"><span>+</span></div>';
    $output .= '<div class="mci-chat-buttons">';

   // WhatsApp Image
if (get_option('mci_whatsapp_enabled') && get_option('mci_whatsapp_number')) {
    $output .= '<a href="https://wa.me/' . esc_attr(get_option('mci_whatsapp_number')) . '" class="mci-chat-button whatsapp" target="_blank">';
    $output .= '<img src="' . get_template_directory_uri() . '/images/WhatsApp.jpg" alt="WhatsApp" style="width: 24px; height: 24px;">';
    $output .= '</a>';
}


    // KakaoTalk SVG
    if (get_option('mci_kakaotalk_enabled') && get_option('mci_kakaotalk_id')) {
        $output .= '<a href="https://story.kakao.com/' . esc_attr(get_option('mci_kakaotalk_id')) . '" class="mci-chat-button kakaotalk" target="_blank">';
        $output .= '<img src="' . get_template_directory_uri() . '/images/KakaoTalk.png" alt="WhatsApp" style="width: 24px; height: 24px;">';
        $output .= '</a>';
    }

    // Zalo SVG
    if (get_option('mci_zalo_enabled') && get_option('mci_zalo_id')) {
        $output .= '<a href="https://zalo.me/' . esc_attr(get_option('mci_zalo_id')) . '" class="mci-chat-button zalo" target="_blank">';
        $output .= '<img src="' . get_template_directory_uri() . '/images/zalo.png" alt="WhatsApp" style="width: 24px; height: 24px;">';
        $output .= '</a>';
    }

    // Facebook Messenger SVG
    if (get_option('mci_facebook_enabled') && get_option('mci_facebook_id')) {
        $output .= '<a href="https://m.me/' . esc_attr(get_option('mci_facebook_id')) . '" class="mci-chat-button facebook" target="_blank">';
        $output .= '<img src="' . get_template_directory_uri() . '/images/facebook.png" alt="WhatsApp" style="width: 24px; height: 24px;">';
        $output .= '</a>';
    }

    // WeChat SVG
    if (get_option('mci_wechat_enabled') && get_option('mci_wechat_id')) {
        $output .= '<a href="weixin://dl/chat?' . esc_attr(get_option('mci_wechat_id')) . '" class="mci-chat-button wechat" target="_blank">';
        $output .= '<img src="' . get_template_directory_uri() . '/images/WeChat.png" alt="WhatsApp" style="width: 24px; height: 24px;">';
        $output .= '</a>';
    }

    $output .= '</div></div>';
    echo $output;
}
add_action('wp_footer', 'mci_display_chat_buttons');

// Load Text Domain for Translations
function mci_load_textdomain() {
    load_plugin_textdomain('multi-chat-plugin', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'mci_load_textdomain');
?>