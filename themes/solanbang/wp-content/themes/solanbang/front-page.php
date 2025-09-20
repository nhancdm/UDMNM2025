<?php
/*
Template Name: Sol An Bang Theme
*/
get_header(); ?>

<div class="elementor-front-page">
    <?php
    if ( have_posts() ) :
        while ( have_posts() ) : the_post();
            the_content();
        endwhile;
    else :
        _e( 'Sorry, no content found.', 'text-domain' );
    endif;
    ?>
</div>

<?php get_footer(); ?>