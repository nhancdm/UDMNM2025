<?php
/**
 * The template for displaying all single posts
 *
 * @package Nhan
 */

get_header();
?>

<div class="container">
    <main class="site-main">
        <div class="content-area">
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>

                        <div class="entry-meta">
                            <span class="posted-on">
                                <i class="fas fa-calendar-alt"></i>
                                <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                    <?php echo esc_html(get_the_date()); ?>
                                </time>
                                <?php if (get_the_time('U') !== get_the_modified_time('U')) : ?>
                                    <time class="updated" datetime="<?php echo esc_attr(get_the_modified_date('c')); ?>">
                                        <?php printf(esc_html__('Updated on %s', 'nhan'), get_the_modified_date()); ?>
                                    </time>
                                <?php endif; ?>
                            </span>

                            <span class="byline">
                                <i class="fas fa-user"></i>
                                <span class="author vcard">
                                    <?php echo get_avatar(get_the_author_meta('ID'), 32); ?>
                                    <a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                        <?php echo esc_html(get_the_author()); ?>
                                    </a>
                                </span>
                            </span>

                            <?php if (has_category()) : ?>
                                <span class="cat-links">
                                    <i class="fas fa-folder"></i>
                                    <?php echo get_the_category_list(', '); ?>
                                </span>
                            <?php endif; ?>

                            <span class="reading-time">
                                <i class="fas fa-clock"></i>
                                <?php echo nhan_reading_time(); ?>
                            </span>
                        </div>
                    </header>

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-thumbnail">
                            <?php the_post_thumbnail('large', array('class' => 'img-responsive')); ?>
                            <?php if (get_post(get_post_thumbnail_id())->post_excerpt) : ?>
                                <div class="image-caption">
                                    <?php echo wp_kses_post(get_post(get_post_thumbnail_id())->post_excerpt); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="entry-content">
                        <?php
                        the_content();

                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'nhan'),
                            'after'  => '</div>',
                        ));
                        ?>
                    </div>

                    <footer class="entry-footer">
                        <?php if (has_tag()) : ?>
                            <div class="tags-links">
                                <i class="fas fa-tags"></i>
                                <span class="tags-title"><?php esc_html_e('Tags:', 'nhan'); ?></span>
                                <?php echo get_the_tag_list('', ', '); ?>
                            </div>
                        <?php endif; ?>

                        <div class="post-sharing">
                            <h4><?php esc_html_e('Share this post:', 'nhan'); ?></h4>
                            <div class="share-buttons">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
                                   target="_blank" rel="noopener noreferrer" class="share-facebook">
                                    <i class="fab fa-facebook-f"></i>
                                    <span>Facebook</span>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
                                   target="_blank" rel="noopener noreferrer" class="share-twitter">
                                    <i class="fab fa-twitter"></i>
                                    <span>Twitter</span>
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>" 
                                   target="_blank" rel="noopener noreferrer" class="share-linkedin">
                                    <i class="fab fa-linkedin-in"></i>
                                    <span>LinkedIn</span>
                                </a>
                                <a href="mailto:?subject=<?php echo urlencode(get_the_title()); ?>&body=<?php echo urlencode(get_permalink()); ?>" 
                                   class="share-email">
                                    <i class="fas fa-envelope"></i>
                                    <span>Email</span>
                                </a>
                            </div>
                        </div>
                    </footer>
                </article>

                <?php
                // Author bio
                if (get_the_author_meta('description')) :
                ?>
                    <div class="author-bio">
                        <div class="author-avatar">
                            <?php echo get_avatar(get_the_author_meta('ID'), 80); ?>
                        </div>
                        <div class="author-info">
                            <h4 class="author-name">
                                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                    <?php echo esc_html(get_the_author()); ?>
                                </a>
                            </h4>
                            <p class="author-description">
                                <?php echo wp_kses_post(get_the_author_meta('description')); ?>
                            </p>
                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="author-posts-link">
                                <?php printf(esc_html__('View all posts by %s', 'nhan'), get_the_author()); ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <?php
                // Related posts
                $related_posts = nhan_get_related_posts(get_the_ID(), 3);
                if ($related_posts->have_posts()) :
                ?>
                    <div class="related-posts">
                        <h3><?php esc_html_e('Related Posts', 'nhan'); ?></h3>
                        <div class="related-posts-grid">
                            <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                                <article class="related-post">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="related-post-thumbnail">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('nhan-thumbnail'); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <div class="related-post-content">
                                        <h4 class="related-post-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h4>
                                        <div class="related-post-meta">
                                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                                <?php echo esc_html(get_the_date()); ?>
                                            </time>
                                        </div>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    </div>
                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>

                <?php
                // Post navigation
                the_post_navigation(array(
                    'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'nhan') . '</span> <span class="nav-title">%title</span>',
                    'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'nhan') . '</span> <span class="nav-title">%title</span>',
                ));
                ?>

                <?php
                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>

            <?php endwhile; ?>
        </div>

        <?php get_sidebar(); ?>
    </main>
</div>

<?php get_footer(); ?>

<style>
/* Single post specific styles */
.single .entry-header {
    text-align: center;
    margin-bottom: 2rem;
}

.single .entry-title {
    font-size: 2.5rem;
    line-height: 1.2;
    margin-bottom: 1rem;
}

.single .entry-meta {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 1.5rem;
    font-size: 0.9rem;
    color: #666;
}

.single .entry-meta span {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.single .entry-meta .author img {
    border-radius: 50%;
    margin-right: 0.5rem;
}

.post-thumbnail {
    margin: 2rem 0;
    text-align: center;
}

.post-thumbnail img {
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.image-caption {
    font-style: italic;
    color: #666;
    margin-top: 0.5rem;
    font-size: 0.9rem;
}

.entry-footer {
    border-top: 1px solid #eee;
    padding-top: 2rem;
    margin-top: 2rem;
}

.tags-links {
    margin-bottom: 2rem;
}

.tags-links .tags-title {
    font-weight: 600;
    margin-right: 0.5rem;
}

.post-sharing h4 {
    margin-bottom: 1rem;
    color: #2c3e50;
}

.share-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.share-buttons a {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    border-radius: 6px;
    text-decoration: none;
    color: white;
    transition: all 0.3s ease;
}

.share-facebook { background: #1877f2; }
.share-twitter { background: #1da1f2; }
.share-linkedin { background: #0077b5; }
.share-email { background: #666; }

.share-buttons a:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

/* Author bio */
.author-bio {
    display: flex;
    gap: 1.5rem;
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 8px;
    margin: 2rem 0;
}

.author-avatar img {
    border-radius: 50%;
}

.author-info h4 {
    margin-bottom: 0.5rem;
}

.author-info h4 a {
    color: #2c3e50;
    text-decoration: none;
}

.author-info h4 a:hover {
    color: #667eea;
}

.author-description {
    margin-bottom: 1rem;
    line-height: 1.6;
}

.author-posts-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
}

.author-posts-link:hover {
    text-decoration: underline;
}

/* Related posts */
.related-posts {
    margin: 3rem 0;
}

.related-posts h3 {
    text-align: center;
    margin-bottom: 2rem;
    color: #2c3e50;
}

.related-posts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.related-post {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.related-post:hover {
    transform: translateY(-5px);
}

.related-post-thumbnail img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.related-post-content {
    padding: 1rem;
}

.related-post-title {
    margin-bottom: 0.5rem;
}

.related-post-title a {
    color: #2c3e50;
    text-decoration: none;
}

.related-post-title a:hover {
    color: #667eea;
}

.related-post-meta {
    font-size: 0.8rem;
    color: #666;
}

/* Post navigation */
.post-navigation {
    margin: 3rem 0;
}

.post-navigation .nav-links {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.post-navigation a {
    display: block;
    padding: 1.5rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-decoration: none;
    color: #333;
    transition: all 0.3s ease;
}

.post-navigation a:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.post-navigation .nav-subtitle {
    display: block;
    font-size: 0.8rem;
    color: #666;
    margin-bottom: 0.5rem;
}

.post-navigation .nav-title {
    font-weight: 600;
    color: #2c3e50;
}

/* Responsive */
@media (max-width: 768px) {
    .single .entry-title {
        font-size: 2rem;
    }
    
    .single .entry-meta {
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }
    
    .author-bio {
        flex-direction: column;
        text-align: center;
    }
    
    .share-buttons {
        justify-content: center;
    }
    
    .post-navigation .nav-links {
        grid-template-columns: 1fr;
    }
}
</style>

<?php
// Add reading time function to functions.php if not exists
if (!function_exists('nhan_reading_time')) {
    function nhan_reading_time() {
        $content = get_post_field('post_content', get_the_ID());
        $word_count = str_word_count(strip_tags($content));
        $reading_time = ceil($word_count / 200); // Average reading speed: 200 words per minute
        
        if ($reading_time == 1) {
            return '1 ' . __('minute read', 'nhan');
        } else {
            return $reading_time . ' ' . __('minutes read', 'nhan');
        }
    }
}

// Add related posts function to functions.php if not exists
if (!function_exists('nhan_get_related_posts')) {
    function nhan_get_related_posts($post_id, $number_posts = 3) {
        $categories = wp_get_post_categories($post_id);
        
        if ($categories) {
            $args = array(
                'category__in'   => $categories,
                'post__not_in'   => array($post_id),
                'posts_per_page' => $number_posts,
                'post_status'    => 'publish',
                'orderby'        => 'rand'
            );
            
            return new WP_Query($args);
        }
        
        return new WP_Query(array('posts_per_page' => 0));
    }
}
?>
