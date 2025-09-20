<?php

class API_Client {
    private $base_url = 'https://example.com/api/products';

    public function get_products() {
        $response = wp_remote_get($this->base_url);
        if (is_wp_error($response)) {
            throw new Exception('API GET error: ' . $response->get_error_message());
        }
        return json_decode(wp_remote_retrieve_body($response), true);
    }

    public function update_product($product_data) {
        $response = wp_remote_post($this->base_url, [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode($product_data),
        ]);

        if (is_wp_error($response)) {
            throw new Exception('API POST error: ' . $response->get_error_message());
        }

        return json_decode(wp_remote_retrieve_body($response), true);
    }
}