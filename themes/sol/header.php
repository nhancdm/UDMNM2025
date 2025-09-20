<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @package sol
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="masthead" class="site-header">
  <div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-dark">
      <!-- Logo -->
      <a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
        <?php
        if ( has_custom_logo() ) {
          the_custom_logo();
        } else {
          bloginfo( 'name' );
        }
        ?>
      </a>

      <!-- Toggle Button (Mobile) -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'sol' ); ?>">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Menu Center -->
      <?php
      wp_nav_menu( array(
        'theme_location'    => 'menu-1',
        'depth'             => 2,
        'container'         => 'div',
        'container_class'   => 'collapse navbar-collapse justify-content-center',
        'container_id'      => 'navbarResponsive',
        'menu_class'        => 'navbar-nav',
        'fallback_cb'       => '__return_false',
        'walker'            => new WP_Bootstrap_Navwalker(),
      ) );
      ?>

      <!-- Booking Button Right -->
      <div class="header-right ms-auto">
        <a href="#" class="btn btn-danger rounded-pill px-4 py-2">Booking</a>
      </div>
    </nav>
  </div>
</header>

<div id="content" class="site-content">
