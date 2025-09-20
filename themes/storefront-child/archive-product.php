<?php
/**
 * Template for displaying product category pages
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' ); ?>

<div class="container my-5">
    <?php
    do_action( 'woocommerce_before_main_content' );

    if ( woocommerce_product_loop() ) {

        do_action( 'woocommerce_before_shop_loop' );

        woocommerce_product_loop_start();

        if ( wc_get_loop_prop( 'total' ) ) {
            while ( have_posts() ) {
                the_post();

                wc_get_template_part( 'content', 'product' );
            }
        }

        woocommerce_product_loop_end();

        do_action( 'woocommerce_after_shop_loop' );

    } else {
        do_action( 'woocommerce_no_products_found' );
    }

    do_action( 'woocommerce_after_main_content' );
    ?>
</div>

<?php
get_footer( 'shop' );
