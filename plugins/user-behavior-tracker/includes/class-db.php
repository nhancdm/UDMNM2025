<?php

class UBT_DB {
    public static function create_table() {
        global $wpdb;
        $table = $wpdb->prefix . 'user_behavior_logs';

        $sql = "CREATE TABLE IF NOT EXISTS `$table` (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            page_url TEXT NOT NULL,
            ip_address VARCHAR(45),
            clicks INT,
            scroll_depth INT,
            time_spent INT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    public static function insert_log($data) {
        global $wpdb;
        $wpdb->insert($wpdb->prefix . 'user_behavior_logs', $data);
    }
}