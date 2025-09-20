<?php
/**
 * Plugin Name: Advanced Security Monitor
 * Description: Enhanced security with intrusion detection, brute force protection, and file integrity monitoring
 * Version: 1.0
 * Author: xAI Team
 * License: GPLv3
 */

if (!defined('ABSPATH')) {
    exit;
}

class AdvancedSecurityMonitor {
    private $failed_attempts = [];
    private $max_attempts = 5;
    private $lockout_time = 1800; // 30 minutes
    private $whitelist_ips;
    private $log_file = WP_CONTENT_DIR . '/security_log.txt';
    private $security_settings = [
        'notify_email' => '',
        'slack_webhook' => '',
        'max_attempts' => 5,
        'lockout_duration' => 1800,
    ];

    public function __construct() {
        // Load settings
        $this->security_settings = wp_parse_args(get_option('asm_security_settings', []), $this->security_settings);
        $this->whitelist_ips = array_filter(explode(',', get_option('asm_whitelist_ips', '')));
        
        // Hooks
        add_action('wp_login_failed', [$this, 'handle_failed_login'], 10, 2);
        add_action('wp_authenticate', [$this, 'check_ip_lockout'], 1);
        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        register_activation_hook(__FILE__, [$this, 'activate']);
        
        // File integrity check schedule
        add_action('asm_file_integrity_check', [$this, 'check_file_integrity']);
        if (!wp_next_scheduled('asm_file_integrity_check')) {
            wp_schedule_event(time(), 'hourly', 'asm_file_integrity_check');
        }
    }

    public function handle_failed_login($username, $error) {
        $ip = $this->get_ip_address();
        if ($this->is_ip_whitelisted($ip)) {
            return;
        }

        $failed_attempts = get_option('asm_failed_attempts', []);
        if (!isset($failed_attempts[$ip])) {
            $failed_attempts[$ip] = ['count' => 0, 'time' => time()];
        }
        
        $failed_attempts[$ip]['count']++;
        $failed_attempts[$ip]['time'] = time();
        
        if ($failed_attempts[$ip]['count'] >= $this->security_settings['max_attempts']) {
            $this->lock_ip($ip);
            $this->send_notification(
                'Brute Force Attempt',
                sprintf('IP %s locked out after %d failed login attempts', $ip, $failed_attempts[$ip]['count'])
            );
            $failed_attempts[$ip]['count'] = 0; // Reset count after lockout
        }
        
        update_option('asm_failed_attempts', $failed_attempts);
        $this->log_security_event('Failed login attempt from IP: ' . $ip);
    }

    public function check_ip_lockout() {
        $ip = $this->get_ip_address();
        if ($this->is_ip_whitelisted($ip)) {
            return;
        }

        $locked_ips = get_option('asm_locked_ips', []);
        if (isset($locked_ips[$ip]) && $locked_ips[$ip] > time()) {
            wp_die(
                'Your IP has been temporarily locked due to suspicious activity.',
                'Security Lockout',
                ['response' => 403]
            );
        }
    }

    private function lock_ip($ip) {
        $locked_ips = get_option('asm_locked_ips', []);
        $locked_ips[$ip] = time() + $this->security_settings['lockout_duration'];
        update_option('asm_locked_ips', $locked_ips);
    }

    public function check_file_integrity() {
        $core_files = $this->get_core_files();
        $hashes = get_option('asm_file_hashes', []);

        foreach ($core_files as $file) {
            if (!file_exists($file)) {
                continue;
            }
            $current_hash = md5_file($file);
            if (isset($hashes[$file]) && $hashes[$file] !== $current_hash) {
                $this->send_notification(
                    'File Integrity Violation',
                    sprintf('File %s has been modified!', $file)
                );
                $this->log_security_event('File modification detected: ' . $file);
            }
            $hashes[$file] = $current_hash;
        }

        update_option('asm_file_hashes', $hashes);
    }

    private function get_core_files() {
        $files = [];
        $dirs = [ABSPATH . 'wp-includes', ABSPATH . 'wp-admin'];
        
        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                continue;
            }
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            );
            
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $files[] = $file->getPathname();
                }
            }
        }
        return $files;
    }

    private function send_notification($subject, $message) {
        $ip = $this->get_ip_address();
        $time = current_time('mysql');
        
        // Email notification
        if (!empty($this->security_settings['notify_email'])) {
            wp_mail(
                $this->security_settings['notify_email'],
                '[Security Alert] ' . $subject,
                "$message\n\nIP: $ip\nTime: $time"
            );
        }

        // Slack notification
        if (!empty($this->security_settings['slack_webhook'])) {
            wp_remote_post($this->security_settings['slack_webhook'], [
                'body' => json_encode([
                    'text' => "*{$subject}*\n{$message}\nIP: {$ip}\nTime: {$time}"
                ]),
                'headers' => ['Content-Type' => 'application/json'],
            ]);
        }
    }

    private function is_ip_whitelisted($ip) {
        return in_array($ip, $this->whitelist_ips, true);
    }

    private function get_ip_address() {
        $ip = 'unknown';
        $ip_headers = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        
        foreach ($ip_headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = sanitize_text_field(wp_unslash($_SERVER[$header]));
                break;
            }
        }
        
        // Handle multiple IPs in X-Forwarded-For
        if (strpos($ip, ',') !== false) {
            $ip = trim(explode(',', $ip)[0]);
        }
        
        return $ip;
    }

    private function log_security_event($message) {
        if (!is_writable(dirname($this->log_file))) {
            return;
        }
        $log_entry = sprintf("[%s] %s\n", current_time('mysql'), $message);
        file_put_contents($this->log_file, $log_entry, FILE_APPEND | LOCK_EX);
    }

    public function admin_menu() {
        add_options_page(
            'Advanced Security Settings',
            'Security Monitor',
            'manage_options',
            'asm-security',
            [$this, 'settings_page']
        );
    }

    public function settings_page() {
        ?>
<div class="wrap">
    <h1>Advanced Security Monitor Settings</h1>
    <form method="post" action="options.php">
        <?php
                settings_fields('asm_security_settings');
                do_settings_sections('asm-security');
                submit_button();
                ?>
    </form>
</div>
<?php
    }

    public function register_settings() {
        register_setting('asm_security_settings', 'asm_security_settings', [$this, 'sanitize_settings']);
        register_setting('asm_security_settings', 'asm_whitelist_ips', [$this, 'sanitize_whitelist']);

        add_settings_section(
            'asm_main_settings',
            'Security Settings',
            null,
            'asm-security'
        );

        add_settings_field(
            'notify_email',
            'Notification Email',
            [$this, 'render_email_field'],
            'asm-security',
            'asm_main_settings'
        );

        add_settings_field(
            'slack_webhook',
            'Slack Webhook URL',
            [$this, 'render_slack_field'],
            'asm-security',
            'asm_main_settings'
        );

        add_settings_field(
            'whitelist_ips',
            'Whitelisted IPs',
            [$this, 'render_whitelist_field'],
            'asm-security',
            'asm_main_settings'
        );

        add_settings_field(
            'max_attempts',
            'Maximum Login Attempts',
            [$this, 'render_max_attempts_field'],
            'asm-security',
            'asm_main_settings'
        );

        add_settings_field(
            'lockout_duration',
            'Lockout Duration (seconds)',
            [$this, 'render_lockout_duration_field'],
            'asm-security',
            'asm_main_settings'
        );
    }

    public function sanitize_settings($input) {
        $sanitized = [];
        $sanitized['notify_email'] = sanitize_email($input['notify_email'] ?? '');
        $sanitized['slack_webhook'] = esc_url_raw($input['slack_webhook'] ?? '');
        $sanitized['max_attempts'] = absint($input['max_attempts'] ?? 5);
        $sanitized['lockout_duration'] = absint($input['lockout_duration'] ?? 1800);
        return $sanitized;
    }

    public function sanitize_whitelist($input) {
        $ips = array_map('trim', explode("\n", $input));
        $valid_ips = [];
        foreach ($ips as $ip) {
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                $valid_ips[] = $ip;
            }
        }
        return implode(',', $valid_ips);
    }

    public function render_email_field() {
        printf(
            '<input type="email" name="asm_security_settings[notify_email]" value="%s" class="regular-text">',
            esc_attr($this->security_settings['notify_email'])
        );
    }

    public function render_slack_field() {
        printf(
            '<input type="url" name="asm_security_settings[slack_webhook]" value="%s" class="regular-text">',
            esc_attr($this->security_settings['slack_webhook'])
        );
    }

    public function render_whitelist_field() {
        printf(
            '<textarea name="asm_whitelist_ips" class="regular-text" rows="4">%s</textarea><p class="description">Enter one IP per line</p>',
            esc_textarea(implode("\n", $this->whitelist_ips))
        );
    }

    public function render_max_attempts_field() {
        printf(
            '<input type="number" name="asm_security_settings[max_attempts]" value="%d" min="1" class="small-text">',
            esc_attr($this->security_settings['max_attempts'])
        );
    }

    public function render_lockout_duration_field() {
        printf(
            '<input type="number" name="asm_security_settings[lockout_duration]" value="%d" min="60" class="small-text">',
            esc_attr($this->security_settings['lockout_duration'])
        );
    }

    public function activate() {
        update_option('asm_file_hashes', []);
        update_option('asm_locked_ips', []);
        update_option('asm_failed_attempts', []);
        if (!wp_next_scheduled('asm_file_integrity_check')) {
            wp_schedule_event(time(), 'hourly', 'asm_file_integrity_check');
        }
    }
}

// Initialize plugin
if (class_exists('AdvancedSecurityMonitor')) {
    new AdvancedSecurityMonitor();
}

// Clean up on deactivation
register_deactivation_hook(__FILE__, function() {
    wp_clear_scheduled_hook('asm_file_integrity_check');
});
?>