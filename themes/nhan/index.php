<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 *
 * @package Nhan
 */

get_header(); ?>

<div class="container">
    <main class="site-main">
        <div class="content-area">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                            <?php
                            if (is_singular()) :
                                the_title('<h1 class="entry-title">', '</h1>');
                            else :
                                the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                            endif;
                            ?>
                            
                            <?php if ('post' === get_post_type()) : ?>
                                <div class="entry-meta">
                                    <span class="posted-on">
                                        <i class="fa fa-calendar"></i>
                                        <a href="<?php echo esc_url(get_permalink()); ?>" rel="bookmark">
                                            <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                                <?php echo esc_html(get_the_date()); ?>
                                            </time>
                                        </a>
                                    </span>
                                    
                                    <span class="byline">
                                        <i class="fa fa-user"></i>
                                        <span class="author vcard">
                                            <a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                                <?php echo esc_html(get_the_author()); ?>
                                            </a>
                                        </span>
                                    </span>
                                    
                                    <?php if (has_category()) : ?>
                                        <span class="cat-links">
                                            <i class="fa fa-folder"></i>
                                            <?php echo get_the_category_list(', '); ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if (has_tag()) : ?>
                                        <span class="tags-links">
                                            <i class="fa fa-tags"></i>
                                            <?php echo get_the_tag_list('', ', '); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </header>

                        <?php if (has_post_thumbnail() && !is_single()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('large', array('class' => 'img-responsive')); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="entry-content">
                            <?php
                            if (is_single() || is_page()) {
                                the_content();
                            } else {
                                the_excerpt();
                                echo '<a href="' . esc_url(get_permalink()) . '" class="more-link">' . __('Read More', 'nhan') . '</a>';
                            }
                            
                            wp_link_pages(array(
                                'before' => '<div class="page-links">' . esc_html__('Pages:', 'nhan'),
                                'after'  => '</div>',
                            ));
                            ?>
                        </div>

                        <?php if (is_single()) : ?>
                            <footer class="entry-footer">
                                <?php
                                $categories_list = get_the_category_list(', ');
                                $tags_list = get_the_tag_list('', ', ');
                                
                                if ($categories_list || $tags_list) {
                                    echo '<div class="entry-meta-footer">';
                                    
                                    if ($categories_list) {
                                        printf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'nhan') . '</span>', $categories_list);
                                    }
                                    
                                    if ($tags_list) {
                                        printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'nhan') . '</span>', $tags_list);
                                    }
                                    
                                    echo '</div>';
                                }
                                ?>
                            </footer>
                        <?php endif; ?>
                    </article>
                <?php endwhile; ?>

                <?php
                // Pagination
                the_posts_pagination(array(
                    'mid_size'  => 2,
                    'prev_text' => __('&laquo; Previous', 'nhan'),
                    'next_text' => __('Next &raquo;', 'nhan'),
                    'class'     => 'pagination',
                ));
                ?>

            <?php else : ?>
                <article class="no-results not-found">
                    <header class="page-header">
                        <h1 class="page-title"><?php esc_html_e('Nothing here', 'nhan'); ?></h1>
                    </header>

                    <div class="page-content">
                        <?php if (is_home() && current_user_can('publish_posts')) : ?>
                            <p><?php
                                printf(
                                    wp_kses(
                                        __('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'nhan'),
                                        array(
                                            'a' => array(
                                                'href' => array(),
                                            ),
                                        )
                                    ),
                                    esc_url(admin_url('post-new.php'))
                                );
                            ?></p>
                        <?php elseif (is_search()) : ?>
                            <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'nhan'); ?></p>
                            <?php get_search_form(); ?>
                        <?php else : ?>
                            <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'nhan'); ?></p>
                            <?php get_search_form(); ?>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endif; ?>
        </div>

        <?php get_sidebar(); ?>
    </main>
</div>

<?php get_footer(); ?>
