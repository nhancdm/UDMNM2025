<?php
/**
 * Plugin Name: Weather Shortcode
 * Description: Hiển thị thời tiết hiện tại bằng shortcode [weather city="Hanoi"] sử dụng OpenWeatherMap API.
 * Version: 1.0
 * Author: Bạn
 */

define('WPWEATHER_API_KEY', 'your_api_key_here'); // 🔴 Thay bằng API key thật

// Đăng ký shortcode [weather city="Hanoi"]
function wpweather_shortcode($atts) {
    $atts = shortcode_atts(array(
        'city' => 'Hanoi',
    ), $atts);

    $city = sanitize_text_field($atts['city']);
    $cache_key = 'weather_data_' . md5($city);

    // Kiểm tra dữ liệu đã được cache chưa
    $weather_data = get_transient($cache_key);

    if (!$weather_data) {
        // Gọi API từ OpenWeatherMap
        $url = 'https://api.openweathermap.org/data/2.5/weather?q=' . urlencode($city) . '&appid=' . WPWEATHER_API_KEY . '&units=metric&lang=vi';

        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            return '<p>Lỗi kết nối đến máy chủ thời tiết.</p>';
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!isset($data['main'])) {
            return '<p>Không lấy được dữ liệu thời tiết cho thành phố: ' . esc_html($city) . '</p>';
        }

        $weather_data = array(
            'temp' => $data['main']['temp'],
            'desc' => $data['weather'][0]['description'],
            'icon' => $data['weather'][0]['icon'],
            'city' => $data['name'],
        );

        // Lưu vào transient cache trong 10 phút
        set_transient($cache_key, $weather_data, 10 * MINUTE_IN_SECONDS);
    }

    // Render HTML kết quả
    ob_start();
    ?>
    <div class="weather-widget" style="border:1px solid #ddd;padding:10px;border-radius:5px;max-width:250px;">
        <strong><?php echo esc_html($weather_data['city']); ?></strong><br>
        <img src="https://openweathermap.org/img/wn/<?php echo esc_attr($weather_data['icon']); ?>@2x.png" alt="icon" style="vertical-align:middle;">
        <span><?php echo esc_html($weather_data['temp']); ?>°C - <?php echo esc_html(ucfirst($weather_data['desc'])); ?></span>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('weather', 'wpweather_shortcode');
