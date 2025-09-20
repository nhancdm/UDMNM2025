<?php

class OAuth_Provider {
    public static function get_config($provider) {
        $configs = [
            'google' => [
                'client_id' => 'GOOGLE_CLIENT_ID',
                'client_secret' => 'GOOGLE_SECRET',
                'auth_url' => 'https://accounts.google.com/o/oauth2/v2/auth',
                'token_url' => 'https://oauth2.googleapis.com/token',
                'user_url' => 'https://www.googleapis.com/oauth2/v3/userinfo',
                'scope' => 'openid email profile',
            ],
            'github' => [
                'client_id' => 'GITHUB_CLIENT_ID',
                'client_secret' => 'GITHUB_SECRET',
                'auth_url' => 'https://github.com/login/oauth/authorize',
                'token_url' => 'https://github.com/login/oauth/access_token',
                'user_url' => 'https://api.github.com/user',
                'scope' => 'read:user user:email',
            ],
        ];

        return $configs[$provider] ?? null;
    }
}