<?php

class Rating_DB {
    public static function create_table() {
        global $wpdb;
        $table = $wpdb->prefix . 'post_ratings';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            post_id BIGINT NOT NULL,
            ip_address VARCHAR(100) NOT NULL,
            content_rating TINYINT,
            image_rating TINYINT,
            helpful_rating TINYINT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }
}