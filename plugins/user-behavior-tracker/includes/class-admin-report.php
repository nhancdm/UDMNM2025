<?php

class UBT_Admin_Report {
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_menu']);
    }

    public static function add_menu() {
        add_menu_page('User Behavior', 'User Behavior', 'manage_options', 'ubt-report', [__CLASS__, 'render']);
    }

    public static function render() {
        global $wpdb;
        $table = $wpdb->prefix . 'user_behavior_logs';
        $logs = $wpdb->get_results("SELECT page_url, COUNT(*) AS visits, AVG(clicks) AS avg_clicks, AVG(scroll_depth) AS avg_scroll, AVG(time_spent) AS avg_time FROM $table GROUP BY page_url ORDER BY visits DESC LIMIT 20");

        echo '<div class="wrap"><h1>Thống kê hành vi người dùng</h1><table class="widefat"><thead><tr>
        <th>Page</th><th>Lượt truy cập</th><th>Click TB</th><th>Scroll % TB</th><th>Time (s) TB</th></tr></thead><tbody>';

        foreach ($logs as $log) {
            echo "<tr>
                <td>{$log->page_url}</td>
                <td>{$log->visits}</td>
                <td>" . round($log->avg_clicks, 1) . "</td>
                <td>" . round($log->avg_scroll, 1) . "%</td>
                <td>" . round($log->avg_time, 1) . "</td>
            </tr>";
        }

        echo '</tbody></table></div>';
    }
}

UBT_Admin_Report::init();