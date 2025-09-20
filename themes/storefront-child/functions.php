<?php
/**
 * Storefront Child Theme Functions
 */

/**
 * Include WP_Bootstrap_Navwalker
 */
require_once get_stylesheet_directory() . '/wp-bootstrap-navwalker.php';

/**
 * Enqueue parent and child theme styles, plus Bootstrap
 */
function storefront_child_enqueue_styles() {
    // Enqueue parent theme's style
    wp_enqueue_style( 'storefront-parent-style', get_template_directory_uri() . '/style.css' );
     wp_enqueue_script( 'storefront-parent-main', get_template_directory_uri() . '/main.js', array('jquery'), null, true );

    // Enqueue Bootstrap CSS
    wp_enqueue_style( 'bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), '5.3.3' );

    // Enqueue child theme's style (depends on Bootstrap)
    wp_enqueue_style( 'storefront-child-style', get_stylesheet_uri(), array( 'storefront-parent-style', 'bootstrap-css' ), '1.0.1' );

    // Enqueue Bootstrap JS (with Popper.js included)
    wp_enqueue_script( 'bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), '5.3.3', true );
}
add_action( 'wp_enqueue_scripts', 'storefront_child_enqueue_styles' );


// Loại bỏ các action mặc định của Storefront header
function my_custom_remove_storefront_header_actions() {
    remove_action( 'storefront_header', 'storefront_product_search', 40 );
    remove_action( 'storefront_header', 'storefront_primary_navigation', 50 );
    remove_action( 'storefront_header', 'storefront_secondary_navigation', 30 );
    // Cũng có thể cần loại bỏ các wrapper nếu không cần thiết cho cấu trúc Bootstrap
    remove_action( 'storefront_header', 'storefront_primary_navigation_wrapper', 42 );
    remove_action( 'storefront_header', 'storefront_primary_navigation_wrapper_close', 68 );
}
add_action( 'init', 'my_custom_remove_storefront_header_actions' ); // Sử dụng hook 'init' hoặc 'wp_loaded'

// Hook để thêm Bootstrap navbar tùy chỉnh
add_action( 'storefront_header', 'my_custom_bootstrap_navbar', 35 ); // Chọn priority phù hợp
function my_custom_bootstrap_navbar() {
    // Mã HTML và PHP cho Bootstrap navbar sẽ được đặt ở đây (xem phần C)
    // Ví dụ:
    // get_template_part('template-parts/header/navbar-bootstrap'); // Nếu bạn tách ra file riêng
}

// Trong functions.php
function storefront_child_register_nav_menu() {
    register_nav_menus( array(
        'bootstrap_nav' => __( 'Bootstrap Navbar Menu', 'storefront-child' ),
    ) );
}
add_action( 'after_setup_theme', 'storefront_child_register_nav_menu' );



?>