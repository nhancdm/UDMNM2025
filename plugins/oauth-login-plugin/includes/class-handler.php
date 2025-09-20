<?php

class OAuth_Handler {
    public static function render_login_buttons() {
        return '
            <a href="' . self::build_auth_url('google') . '">Login with Google</a> |
            <a href="' . self::build_auth_url('github') . '">Login with GitHub</a>
        ';
    }

    public static function build_auth_url($provider) {
        $conf = OAuth_Provider::get_config($provider);
        $params = [
            'client_id' => $conf['client_id'],
            'redirect_uri' => OAUTH_REDIRECT_URI,
            'response_type' => 'code',
            'scope' => $conf['scope'],
            'state' => $provider
        ];
        return $conf['auth_url'] . '?' . http_build_query($params);
    }

    public static function add_callback_rewrite() {
        add_rewrite_rule('^oauth-callback/?$', 'index.php?oauth_callback=1', 'top');
        flush_rewrite_rules();
    }

    public static function listen_callback() {
        add_filter('query_vars', function ($vars) {
            $vars[] = 'oauth_callback';
            return $vars;
        });

        add_action('template_redirect', function () {
            if (get_query_var('oauth_callback')) {
                self::handle_callback();
                exit;
            }
        });
    }

    public static function handle_callback() {
        $code = $_GET['code'] ?? null;
        $state = $_GET['state'] ?? null;

        if (!$code || !$state) {
            wp_die('OAuth failed: missing code or state');
        }

        $conf = OAuth_Provider::get_config($state);

        // 1. Exchange code for token
        $token_response = wp_remote_post($conf['token_url'], [
            'body' => [
                'client_id' => $conf['client_id'],
                'client_secret' => $conf['client_secret'],
                'code' => $code,
                'redirect_uri' => OAUTH_REDIRECT_URI,
                'grant_type' => 'authorization_code',
            ],
            'headers' => ['Accept' => 'application/json']
        ]);
        $token_data = json_decode(wp_remote_retrieve_body($token_response), true);
        $access_token = $token_data['access_token'] ?? null;

        if (!$access_token) wp_die('OAuth failed: token missing');

        // 2. Get user info
        $user_response = wp_remote_get($conf['user_url'], [
            'headers' => [
                'Authorization' => 'Bearer ' . $access_token,
                'User-Agent' => 'WordPress OAuth Plugin'
            ]
        ]);
        $user_info = json_decode(wp_remote_retrieve_body($user_response), true);

        // 3. Get user email
        $email = $user_info['email'] ?? $user_info['login'] . "@github.com"; // fallback for GitHub

        // 4. Find or create WP user
        $user = get_user_by('email', $email);
        if (!$user) {
            $user_id = wp_create_user(sanitize_user($user_info['name'] ?? $user_info['login']), wp_generate_password(), $email);
            $user = get_user_by('id', $user_id);
        }

        // 5. Save token (hashed or encrypted)
        update_user_meta($user->ID, 'oauth_token_' . $state, wp_hash($access_token));

        // 6. Log in
        wp_set_auth_cookie($user->ID, true);
        wp_redirect(home_url());
        exit;
    }
}