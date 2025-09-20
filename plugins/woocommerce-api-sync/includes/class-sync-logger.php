<?php

class Sync_Logger {
    private $log_file;

    public function __construct() {
        $upload_dir = wp_upload_dir();
        $this->log_file = $upload_dir['basedir'] . '/wc-api-sync.log';
    }

    public function log($message) {
        $time = date('Y-m-d H:i:s');
        error_log("[$time] $message\n", 3, $this->log_file);
    }
}