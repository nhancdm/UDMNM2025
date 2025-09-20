<?php
add_action('wp_ajax_nopriv_abt_track', 'abt_handle_tracking');
add_action('wp_ajax_abt_track', 'abt_handle_tracking');

function abt_handle_tracking() {
    global $wpdb;
    $post_id = intval($_POST['post_id']);
    $variation = sanitize_text_field($_POST['variation']);
    $metric = sanitize_text_field($_POST['metric']);
    $value = floatval($_POST['value']);

    $wpdb->insert($wpdb->prefix . 'abt_data', [
        'post_id' => $post_id,
        'variation' => $variation,
        'metric' => $metric,
        'value' => $value,
    ]);

    wp_send_json_success();
}