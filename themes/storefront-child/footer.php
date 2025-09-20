<?php
/**
 * The template for displaying the footer.
 *
 * @package storefront-child
 */
?>
        </div><!-- #content -->
        <footer id="colophon" class="site-footer bg-dark text-white py-4" role="contentinfo">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h3><?php bloginfo( 'name' ); ?></h3>
                        <p><?php bloginfo( 'description' ); ?></p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'footer',
                            'container'      => false,
                            'menu_class'     => 'list-inline',
                            'fallback_cb'    => false,
                        ) );
                        ?>
                        <p>&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </footer><!-- #colophon -->
    </div><!-- #page -->
    <?php wp_footer(); ?>
</body>
</html>