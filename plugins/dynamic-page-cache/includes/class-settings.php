<?php

class DPC_Settings {
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_menu']);
        add_action('admin_init', [__CLASS__, 'register_settings']);
    }

    public static function add_menu() {
        add_options_page('Dynamic Cache Settings', 'Dynamic Cache', 'manage_options', 'dpc-settings', [__CLASS__, 'settings_page']);
    }

    public static function register_settings() {
        register_setting('dpc_settings_group', 'dpc_cache_mode');
    }

    public static function settings_page() {
        ?>
<div class="wrap">
    <h1>Dynamic Cache Settings</h1>
    <form method="post" action="options.php">
        <?php settings_fields('dpc_settings_group'); ?>
        <?php do_settings_sections('dpc_settings_group'); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Cache storage mode</th>
                <td>
                    <select name="dpc_cache_mode">
                        <option value="file" <?php selected(get_option('dpc_cache_mode'), 'file'); ?>>File</option>
                        <option value="db" <?php selected(get_option('dpc_cache_mode'), 'db'); ?>>Database (transient)
                        </option>
                    </select>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>
<?php
    }
}

DPC_Settings::init();