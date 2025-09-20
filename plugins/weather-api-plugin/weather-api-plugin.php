<?php
/*
Plugin Name: Weather API Plugin
Description: Hiển thị thông tin thời tiết hiện tại của một thành phố qua shortcode [weather city="Hanoi"].
Version: 1.0
Author: Your Name
*/

if (!defined('ABSPATH')) {
    exit; // Ngăn chặn truy cập trực tiếp
}

// Đổi YOUR_API_KEY thành API key bạn lấy ở bước 1
define('WAP_API_KEY', 'a0250761cabc6e240d904c9a0e6b0879');

// Hàm lấy dữ liệu thời tiết
function wap_get_weather_data($city) {
    // Tạo khóa cache riêng cho từng city
    $transient_key = 'wap_weather_' . sanitize_title($city);

    // Kiểm tra xem có dữ liệu cache chưa
    $cached = get_transient($transient_key);
    if ($cached !== false) {
        return $cached; // Trả về dữ liệu cache
    }

    // URL API OpenWeatherMap
    $url = add_query_arg([
        'q' => urlencode($city),
        'appid' => WAP_API_KEY,
        'units' => 'metric',
        'lang' => 'vi',
    ], 'https://api.openweathermap.org/data/2.5/weather');

    // Gọi API
    $response = wp_remote_get($url, ['timeout' => 10]);

    if (is_wp_error($response)) {
        // Lỗi khi gọi API
        return new WP_Error('api_error', __('Không thể lấy dữ liệu thời tiết. Vui lòng thử lại sau.', 'weather-api-plugin'));
    }

    $code = wp_remote_retrieve_response_code($response);
    if ($code != 200) {
        // API trả về lỗi
        return new WP_Error('api_error', __('Dữ liệu thời tiết không khả dụng.', 'weather-api-plugin'));
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (empty($data) || !isset($data['weather'])) {
        return new WP_Error('api_error', __('Dữ liệu thời tiết không hợp lệ.', 'weather-api-plugin'));
    }

    // Lưu cache 10 phút
    set_transient($transient_key, $data, 600);

    return $data;
}

// Hàm render shortcode
function wap_render_weather_shortcode($atts) {
    $atts = shortcode_atts(['city' => 'Hanoi'], $atts, 'weather');
    $city = sanitize_text_field($atts['city']);
    $weather_data = wap_get_weather_data($city);

    if (is_wp_error($weather_data)) {
        return '<div class="wap-error">' . esc_html($weather_data->get_error_message()) . '</div>';
    }

    $temp = isset($weather_data['main']['temp']) ? round($weather_data['main']['temp']) : '-';
    $desc = isset($weather_data['weather'][0]['description']) ? ucfirst($weather_data['weather'][0]['description']) : '';
    $icon_code = isset($weather_data['weather'][0]['icon']) ? $weather_data['weather'][0]['icon'] : '';
    $icon_url = "https://openweathermap.org/img/wn/{$icon_code}@2x.png";

    ob_start();
    ?>
    <div class="wap-weather-widget" style="border:1px solid #ddd;padding:10px;max-width:200px;text-align:center;font-family:sans-serif;">
        <h4>Thời tiết tại <?php echo esc_html($city); ?></h4>
        <img src="<?php echo esc_url($icon_url); ?>" alt="<?php echo esc_attr($desc); ?>" />
        <div style="font-size:24px;font-weight:bold;"><?php echo esc_html($temp); ?>°C</div>
        <div style="text-transform: capitalize;"><?php echo esc_html($desc); ?></div>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode('weather', 'wap_render_weather_shortcode');
