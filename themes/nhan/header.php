<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @package Nhan
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'nhan'); ?></a>

    <header id="masthead" class="site-header travel-header">
        <!-- Top hotline bar -->
        <div class="topbar">
            <div class="container topbar-inner">
                <div class="hotline">
                    <i class="fas fa-phone-volume" aria-hidden="true"></i>
                    <span class="label"><?php esc_html_e('Hotline', 'nhan'); ?>:</span>
                    <a href="tel:<?php echo esc_attr( preg_replace('/\s+/', '', get_theme_mod('nhan_hotline_number', '1900 1177')) ); ?>" class="number">
                        <?php echo esc_html( get_theme_mod('nhan_hotline_number', '1900 1177') ); ?>
                    </a>
                </div>
                <div class="top-actions">
                    <?php if (has_nav_menu('topbar')) : ?>
                        <?php wp_nav_menu(array(
                            'theme_location' => 'topbar',
                            'menu_id'        => 'topbar-menu',
                            'container'      => false,
                            'depth'          => 1,
                        )); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="container header-inner">
            <div class="site-branding">
                <?php
                if (has_custom_logo()) {
                    the_custom_logo();
                } else {
                    ?>
                    <h1 class="site-title">
                        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                            <?php bloginfo('name'); ?>
                        </a>
                    </h1>
                    <?php
                }

                $nhan_description = get_bloginfo('description', 'display');
                if ($nhan_description || is_customize_preview()) {
                    ?>
                    <p class="site-description"><?php echo $nhan_description; ?></p>
                    <?php
                }
                ?>
            </div>
            <div class="header-search-wide">
                <?php get_search_form(); ?>
            </div>
            <div class="header-cta">
                <a class="btn-hotline" href="tel:<?php echo esc_attr( preg_replace('/\s+/', '', get_theme_mod('nhan_hotline_number', '1900 1177')) ); ?>">
                    <i class="fas fa-headset" aria-hidden="true"></i>
                    <span><?php esc_html_e('Tư vấn ngay', 'nhan'); ?></span>
                </a>
            </div>
        </div>

        <nav id="site-navigation" class="main-navigation travel-nav">
            <div class="container">
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    <span class="menu-toggle-text"><?php esc_html_e('Menu', 'nhan'); ?></span>
                    <span class="menu-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'container'      => false,
                    'fallback_cb'    => 'nhan_fallback_menu',
                ));
                ?>
            </div>
        </nav>
    </header>

    <?php
    // Display breadcrumb on all pages except home
    if (!is_home() && !is_front_page()) {
        echo '<div class="container">';
        nhan_breadcrumb();
        echo '</div>';
    }
    ?>

    <div id="content" class="site-content">

<?php
/**
 * Fallback menu for when no menu is assigned
 */
function nhan_fallback_menu() {
    echo '<ul id="primary-menu" class="menu">';
    echo '<li><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'nhan') . '</a></li>';
    
    // Show pages in menu
    $pages = get_pages(array('sort_column' => 'menu_order'));
    foreach ($pages as $page) {
        echo '<li><a href="' . esc_url(get_permalink($page->ID)) . '">' . esc_html($page->post_title) . '</a></li>';
    }
    
    // Show categories
    $categories = get_categories(array('number' => 5));
    foreach ($categories as $category) {
        echo '<li><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a></li>';
    }
    
    echo '</ul>';
}
?>
