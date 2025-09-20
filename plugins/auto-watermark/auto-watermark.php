<?php
/**
 * Plugin Name: Auto Watermark Images
 * Description: Tự động thêm watermark (văn bản) vào ảnh được tải lên WordPress.
 * Version: 1.0
 * Author: Bạn
 */

// 1. Settings API để tùy chỉnh văn bản watermark
function awm_register_settings() {
    add_option('awm_watermark_text', '© My Website');
    register_setting('awm_options_group', 'awm_watermark_text');
}
add_action('admin_init', 'awm_register_settings');

function awm_settings_page() {
    ?>
    <div class="wrap">
        <h1>Watermark Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('awm_options_group'); ?>
            <label for="awm_watermark_text">Watermark text:</label>
            <input type="text" name="awm_watermark_text" id="awm_watermark_text" value="<?php echo esc_attr(get_option('awm_watermark_text')); ?>" size="40" />
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
function awm_add_menu() {
    add_options_page('Watermark Settings', 'Watermark Settings', 'manage_options', 'awm-settings', 'awm_settings_page');
}
add_action('admin_menu', 'awm_add_menu');

// 2. Thêm watermark khi ảnh được tải lên
function awm_add_watermark_on_upload($upload) {
    $file_path = $upload['file'];
    $mime_type = mime_content_type($file_path);

    // Chỉ xử lý với ảnh JPG và PNG
    if (!in_array($mime_type, ['image/jpeg', 'image/png'])) return $upload;

    $watermark_text = get_option('awm_watermark_text', '© My Website');

    if (!function_exists('imagecreatefromjpeg')) return $upload; // GD chưa bật

    // Tải ảnh gốc
    $image = null;
    if ($mime_type === 'image/jpeg') {
        $image = imagecreatefromjpeg($file_path);
    } elseif ($mime_type === 'image/png') {
        $image = imagecreatefrompng($file_path);
    }

    if (!$image) return $upload;

    $black = imagecolorallocate($image, 0, 0, 0);
    $font_size = 5; // GD built-in font size
    $image_width = imagesx($image);
    $image_height = imagesy($image);
    $text_width = imagefontwidth($font_size) * strlen($watermark_text);
    $text_height = imagefontheight($font_size);
    $x = $image_width - $text_width - 10;
    $y = $image_height - $text_height - 10;

    // Thêm watermark
    imagestring($image, $font_size, $x, $y, $watermark_text, $black);

    // Ghi đè lại ảnh
    if ($mime_type === 'image/jpeg') {
        imagejpeg($image, $file_path);
    } elseif ($mime_type === 'image/png') {
        imagepng($image, $file_path);
    }

    imagedestroy($image);
    return $upload;
}
add_filter('wp_handle_upload', 'awm_add_watermark_on_upload');
