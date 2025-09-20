<?php
/**
 * Template Name: Trang Chủ
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="hfeed site">
    <header id="masthead" class="site-header" role="banner">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <!-- Site Branding -->
                <a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <?php echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'medium', false, array( 'class' => 'custom-logo' ) ); ?>
                    <span class="site-title"><?php bloginfo( 'name' ); ?></span>
                </a>

                <!-- Toggler for mobile -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#primary-nav" aria-controls="primary-nav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Primary Navigation -->
                <div class="collapse navbar-collapse" id="primary-nav">
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => 'navbar-nav me-auto mb-2 mb-lg-0',
                        'fallback_cb'    => false,
                        'walker'         => new WP_Bootstrap_Navwalker(),
                    ) );
                    ?>
                    
    <!-- Form tìm kiếm -->
    <form class="d-flex" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <input class="form-control me-2" type="search" placeholder="Tìm kiếm..." aria-label="Search" value="<?php echo get_search_query(); ?>" name="s">
        <button class="btn btn-outline-success" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
    </form>


                    <!-- Secondary Navigation (Cart, Search, etc.) -->
                    <ul class="navbar-nav">
                        <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
                                    <span class="fa fa-shopping-cart"></span> Cart
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header><!-- #masthead -->
    <div id="content" class="site-content" tabindex="-1">