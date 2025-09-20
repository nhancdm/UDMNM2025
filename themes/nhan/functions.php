<?php
/**
 * Nhan Theme functions and definitions
 *
 * @package Nhan
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Inject dropdown items for "Giới thiệu" from categories
 * - Add CSS class "intro-from-cats" to the desired menu item in Appearance > Menus
 * - Optionally set a parent category slug in Customizer
 */
function nhan_dynamic_intro_dropdown($items, $args) {
    if ($args->theme_location !== 'primary') {
        return $items;
    }

    // Find the target menu item (by class or by title)
    $target_index = null;
    foreach ($items as $index => $item) {
        $classes = is_array($item->classes) ? $item->classes : array();
        $title   = isset($item->title) ? wp_strip_all_tags($item->title) : '';

        if (in_array('intro-from-cats', $classes, true) || mb_strtolower($title) === mb_strtolower(__('Giới thiệu', 'nhan'))) {
            $target_index = $index;
            break;
        }
    }

    if ($target_index === null) {
        return $items;
    }

    $parent_item = $items[$target_index];

    // Get parent category by slug from Customizer (optional)
    $parent_slug = get_theme_mod('nhan_intro_parent_cat_slug', '');
    $parent_id   = 0;
    if (!empty($parent_slug)) {
        $term = get_term_by('slug', sanitize_title($parent_slug), 'category');
        if ($term && !is_wp_error($term)) {
            $parent_id = (int) $term->term_id;
        }
    }

    // Fetch categories to list as submenu
    $cats = get_categories(array(
        'parent'      => $parent_id,
        'hide_empty'  => false,
        'number'      => 12,
        'orderby'     => 'name',
        'order'       => 'ASC',
    ));

    if (empty($cats)) {
        return $items;
    }

    // Ensure the parent item is marked as having children
    if (!in_array('menu-item-has-children', $parent_item->classes, true)) {
        $parent_item->classes[] = 'menu-item-has-children';
        $items[$target_index] = $parent_item;
    }

    // Build new menu item objects as children of the target item
    $base_id = -10000; // negative IDs for temporary items
    foreach ($cats as $i => $cat) {
        $new = (object) array(
            'ID'                => $base_id - $i,
            'db_id'             => 0,
            'menu_item_parent'  => (int) $parent_item->ID,
            'object_id'         => (int) $cat->term_id,
            'object'            => 'category',
            'type'              => 'taxonomy',
            'type_label'        => __('Category', 'nhan'),
            'title'             => $cat->name,
            'url'               => get_category_link($cat->term_id),
            'target'            => '',
            'attr_title'        => '',
            'description'       => '',
            'classes'           => array('menu-item', 'menu-item-type-taxonomy', 'menu-item-object-category'),
            'xfn'               => '',
            'status'            => '',
        );
        $items[] = $new;
    }

    return $items;
}
add_filter('wp_nav_menu_objects', 'nhan_dynamic_intro_dropdown', 10, 2);

/**
 * Theme setup
 */
function nhan_setup() {
    // Make theme available for translation
    load_theme_textdomain('nhan', get_template_directory() . '/languages');

    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');

    // Add support for responsive embedded content
    add_theme_support('responsive-embeds');

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Add support for wide alignment
    add_theme_support('align-wide');

    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // Add support for custom header
    add_theme_support('custom-header', array(
        'default-color' => 'ffffff',
        'width'         => 1200,
        'height'        => 400,
        'flex-height'   => true,
        'flex-width'    => true,
    ));

    // Add support for custom background
    add_theme_support('custom-background', array(
        'default-color' => 'f8f9fa',
    ));

    // Add support for HTML5 markup
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Add support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'nhan'),
        'footer'  => esc_html__('Footer Menu', 'nhan'),
        'topbar'  => esc_html__('Topbar Menu', 'nhan'),
    ));

    // Set content width
    if (!isset($content_width)) {
        $content_width = 800;
    }
}
add_action('after_setup_theme', 'nhan_setup');

/**
 * Enqueue scripts and styles
 */
function nhan_scripts() {
    // Enqueue theme stylesheet
    wp_enqueue_style('nhan-style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version'));

    // Enqueue Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', array(), '6.0.0');

    // Enqueue Google Fonts
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap', array(), null);

    // Enqueue theme JavaScript
    wp_enqueue_script('nhan-script', get_template_directory_uri() . '/js/theme.js', array('jquery'), wp_get_theme()->get('Version'), true);

    // Enqueue comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'nhan_scripts');

/**
 * Register widget areas
 */
function nhan_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Primary Sidebar', 'nhan'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here to appear in your primary sidebar.', 'nhan'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Widget Area 1', 'nhan'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Add widgets here to appear in the first footer widget area.', 'nhan'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Widget Area 2', 'nhan'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Add widgets here to appear in the second footer widget area.', 'nhan'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Widget Area 3', 'nhan'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Add widgets here to appear in the third footer widget area.', 'nhan'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'nhan_widgets_init');

/**
 * Custom excerpt length
 */
function nhan_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'nhan_excerpt_length');

/**
 * Custom excerpt more
 */
function nhan_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'nhan_excerpt_more');

/**
 * Add custom image sizes
 */
function nhan_image_sizes() {
    add_image_size('nhan-featured', 800, 400, true);
    add_image_size('nhan-thumbnail', 300, 200, true);
}
add_action('after_setup_theme', 'nhan_image_sizes');

/**
 * Customize the login page
 */
function nhan_login_logo() {
    ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: none;
            background-size: contain;
            width: auto;
            height: auto;
            text-indent: 0;
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            text-decoration: none;
        }
        .login form {
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .wp-core-ui .button-primary {
            background: #667eea;
            border-color: #667eea;
            border-radius: 4px;
        }
        .wp-core-ui .button-primary:hover {
            background: #5a6fd8;
            border-color: #5a6fd8;
        }
    </style>
    <?php
}
add_action('login_enqueue_scripts', 'nhan_login_logo');

/**
 * Change login logo URL
 */
function nhan_login_logo_url() {
    return home_url();
}
add_filter('login_headerurl', 'nhan_login_logo_url');

/**
 * Change login logo title
 */
function nhan_login_logo_url_title() {
    return get_bloginfo('name');
}
add_filter('login_headertitle', 'nhan_login_logo_url_title');

/**
 * Add custom post types support
 */
function nhan_add_post_type_support() {
    add_post_type_support('page', 'excerpt');
}
add_action('init', 'nhan_add_post_type_support');

/**
 * Remove unnecessary WordPress features
 */
function nhan_remove_wp_features() {
    // Remove WordPress version from head
    remove_action('wp_head', 'wp_generator');
    
    // Remove RSD link
    remove_action('wp_head', 'rsd_link');
    
    // Remove wlwmanifest.xml
    remove_action('wp_head', 'wlwmanifest_link');
    
    // Remove shortlink
    remove_action('wp_head', 'wp_shortlink_wp_head');
    
    // Remove feed links
    remove_action('wp_head', 'feed_links_extra', 3);
}
add_action('init', 'nhan_remove_wp_features');

/**
 * Add security headers
 */
function nhan_security_headers() {
    if (!is_admin()) {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
    }
}
add_action('send_headers', 'nhan_security_headers');

/**
 * Optimize WordPress performance
 */
function nhan_optimize_performance() {
    // Remove query strings from static resources
    if (!is_admin()) {
        add_filter('script_loader_src', 'nhan_remove_query_strings', 15, 1);
        add_filter('style_loader_src', 'nhan_remove_query_strings', 15, 1);
    }
}
add_action('init', 'nhan_optimize_performance');

function nhan_remove_query_strings($src) {
    $parts = explode('?ver', $src);
    return $parts[0];
}

/**
 * Add theme customizer options
 */
function nhan_customize_register($wp_customize) {
    // Add theme options section
    $wp_customize->add_section('nhan_theme_options', array(
        'title'    => __('Nhan Theme Options', 'nhan'),
        'priority' => 30,
    ));

    // Add footer text setting
    $wp_customize->add_setting('nhan_footer_text', array(
        'default'           => __('© 2024 Nhan Theme. All rights reserved.', 'nhan'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('nhan_footer_text', array(
        'label'   => __('Footer Text', 'nhan'),
        'section' => 'nhan_theme_options',
        'type'    => 'text',
    ));

    // Add social media links
    $social_networks = array(
        'facebook'  => 'Facebook',
        'twitter'   => 'Twitter',
        'instagram' => 'Instagram',
        'linkedin'  => 'LinkedIn',
        'youtube'   => 'YouTube',
    );

    foreach ($social_networks as $network => $label) {
        $wp_customize->add_setting('nhan_' . $network . '_url', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control('nhan_' . $network . '_url', array(
            'label'   => $label . ' URL',
            'section' => 'nhan_theme_options',
            'type'    => 'url',
        ));
    }

    // Hotline number setting
    $wp_customize->add_setting('nhan_hotline_number', array(
        'default'           => '1900 1177',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('nhan_hotline_number', array(
        'label'       => __('Hotline Number', 'nhan'),
        'description' => __('Displayed in the topbar and hotline button', 'nhan'),
        'section'     => 'nhan_theme_options',
        'type'        => 'text',
    ));

    // Search placeholder setting
    $wp_customize->add_setting('nhan_search_placeholder', array(
        'default'           => __('Bạn muốn đi du lịch ở đâu?', 'nhan'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('nhan_search_placeholder', array(
        'label'       => __('Search Placeholder', 'nhan'),
        'description' => __('Text shown inside the large header search box', 'nhan'),
        'section'     => 'nhan_theme_options',
        'type'        => 'text',
    ));

    // Intro parent category slug (for Giới thiệu dropdown)
    $wp_customize->add_setting('nhan_intro_parent_cat_slug', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_title',
    ));

    $wp_customize->add_control('nhan_intro_parent_cat_slug', array(
        'label'       => __('Giới thiệu - Parent Category Slug', 'nhan'),
        'description' => __('If set, the dropdown for Giới thiệu will list child categories of this parent slug. Leave empty to list top-level categories.', 'nhan'),
        'section'     => 'nhan_theme_options',
        'type'        => 'text',
    ));
}
add_action('customize_register', 'nhan_customize_register');

/**
 * Get social media links
 */
function nhan_get_social_links() {
    $social_networks = array(
        'facebook'  => 'fab fa-facebook-f',
        'twitter'   => 'fab fa-twitter',
        'instagram' => 'fab fa-instagram',
        'linkedin'  => 'fab fa-linkedin-in',
        'youtube'   => 'fab fa-youtube',
    );

    $social_links = '';
    foreach ($social_networks as $network => $icon) {
        $url = get_theme_mod('nhan_' . $network . '_url');
        if ($url) {
            $social_links .= '<a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer"><i class="' . $icon . '"></i></a>';
        }
    }

    return $social_links;
}

/**
 * Add breadcrumb navigation
 */
function nhan_breadcrumb() {
    if (!is_home() && !is_front_page()) {
        echo '<nav class="breadcrumb">';
        echo '<a href="' . home_url() . '">' . __('Home', 'nhan') . '</a>';
        
        if (is_category() || is_single()) {
            echo ' &raquo; ';
            the_category(' &bull; ');
            if (is_single()) {
                echo ' &raquo; ';
                the_title();
            }
        } elseif (is_page()) {
            echo ' &raquo; ';
            echo the_title();
        } elseif (is_search()) {
            echo ' &raquo; ' . __('Search Results for', 'nhan') . ' "' . get_search_query() . '"';
        } elseif (is_tag()) {
            echo ' &raquo; ' . __('Tag', 'nhan') . ' "' . single_tag_title('', false) . '"';
        } elseif (is_author()) {
            echo ' &raquo; ' . __('Author', 'nhan') . ' "' . get_the_author() . '"';
        } elseif (is_404()) {
            echo ' &raquo; ' . __('404 Not Found', 'nhan');
        }
        
        echo '</nav>';
    }
}

/**
 * Add theme support for Gutenberg
 */
function nhan_gutenberg_support() {
    // Add support for editor color palette
    add_theme_support('editor-color-palette', array(
        array(
            'name'  => __('Primary', 'nhan'),
            'slug'  => 'primary',
            'color' => '#667eea',
        ),
        array(
            'name'  => __('Secondary', 'nhan'),
            'slug'  => 'secondary',
            'color' => '#764ba2',
        ),
        array(
            'name'  => __('Dark', 'nhan'),
            'slug'  => 'dark',
            'color' => '#2c3e50',
        ),
        array(
            'name'  => __('Light', 'nhan'),
            'slug'  => 'light',
            'color' => '#f8f9fa',
        ),
    ));

    // Add support for custom font sizes
    add_theme_support('editor-font-sizes', array(
        array(
            'name' => __('Small', 'nhan'),
            'size' => 14,
            'slug' => 'small'
        ),
        array(
            'name' => __('Normal', 'nhan'),
            'size' => 16,
            'slug' => 'normal'
        ),
        array(
            'name' => __('Large', 'nhan'),
            'size' => 24,
            'slug' => 'large'
        ),
        array(
            'name' => __('Extra Large', 'nhan'),
            'size' => 32,
            'slug' => 'extra-large'
        )
    ));
}
add_action('after_setup_theme', 'nhan_gutenberg_support');
