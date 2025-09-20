<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
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
                        
                        <?php if (has_excerpt()) : ?>
                            <div class="entry-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                        <?php endif; ?>
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

                    <?php if (get_edit_post_link()) : ?>
                        <footer class="entry-footer">
                            <?php
                            edit_post_link(
                                sprintf(
                                    wp_kses(
                                        __('Edit <span class="screen-reader-text">%s</span>', 'nhan'),
                                        array(
                                            'span' => array(
                                                'class' => array(),
                                            ),
                                        )
                                    ),
                                    get_the_title()
                                ),
                                '<span class="edit-link">',
                                '</span>'
                            );
                            ?>
                        </footer>
                    <?php endif; ?>
                </article>

                <?php
                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>

            <?php endwhile; ?>
        </div>

        <?php
        // Show sidebar only if it's not a full-width page template
        if (!is_page_template('page-templates/full-width.php')) {
            get_sidebar();
        }
        ?>
    </main>
</div>

<?php get_footer(); ?>

<style>
/* Page specific styles */
.page .entry-header {
    text-align: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #eee;
}

.page .entry-title {
    font-size: 2.5rem;
    line-height: 1.2;
    margin-bottom: 1rem;
    color: #2c3e50;
}

.page .entry-excerpt {
    font-size: 1.2rem;
    color: #666;
    font-style: italic;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

.page .post-thumbnail {
    margin: 2rem 0;
    text-align: center;
}

.page .post-thumbnail img {
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    max-width: 100%;
    height: auto;
}

.page .image-caption {
    font-style: italic;
    color: #666;
    margin-top: 0.5rem;
    font-size: 0.9rem;
}

.page .entry-content {
    line-height: 1.8;
}

.page .entry-content h2,
.page .entry-content h3,
.page .entry-content h4,
.page .entry-content h5,
.page .entry-content h6 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    color: #2c3e50;
}

.page .entry-content h2 {
    font-size: 2rem;
    border-bottom: 2px solid #667eea;
    padding-bottom: 0.5rem;
}

.page .entry-content h3 {
    font-size: 1.5rem;
}

.page .entry-content h4 {
    font-size: 1.3rem;
}

.page .entry-content p {
    margin-bottom: 1.5rem;
}

.page .entry-content ul,
.page .entry-content ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.page .entry-content li {
    margin-bottom: 0.5rem;
}

.page .entry-content blockquote {
    background: #f8f9fa;
    border-left: 4px solid #667eea;
    padding: 1.5rem;
    margin: 2rem 0;
    font-style: italic;
    border-radius: 0 8px 8px 0;
}

.page .entry-content blockquote p:last-child {
    margin-bottom: 0;
}

.page .entry-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 2rem 0;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.page .entry-content th,
.page .entry-content td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.page .entry-content th {
    background: #667eea;
    color: white;
    font-weight: 600;
}

.page .entry-content tr:hover {
    background: #f8f9fa;
}

.page .entry-content code {
    background: #f8f9fa;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
    color: #e83e8c;
}

.page .entry-content pre {
    background: #2c3e50;
    color: #ecf0f1;
    padding: 1.5rem;
    border-radius: 8px;
    overflow-x: auto;
    margin: 2rem 0;
}

.page .entry-content pre code {
    background: none;
    color: inherit;
    padding: 0;
}

.page .entry-footer {
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 1px solid #eee;
    text-align: center;
}

.page .edit-link a {
    background: #667eea;
    color: white;
    padding: 0.5rem 1rem;
    text-decoration: none;
    border-radius: 4px;
    font-size: 0.9rem;
    transition: background-color 0.3s ease;
}

.page .edit-link a:hover {
    background: #5a6fd8;
}

/* Page links (pagination for multi-page content) */
.page-links {
    margin: 2rem 0;
    text-align: center;
}

.page-links a,
.page-links > span {
    display: inline-block;
    padding: 0.5rem 1rem;
    margin: 0 0.25rem;
    background: white;
    border: 1px solid #ddd;
    text-decoration: none;
    color: #333;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.page-links a:hover {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.page-links > span {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

/* Full-width page template support */
.page-template-full-width .site-main {
    grid-template-columns: 1fr;
}

.page-template-full-width .content-area {
    max-width: none;
}

/* Contact form styling (if using Contact Form 7 or similar) */
.page .wpcf7-form {
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 8px;
    margin: 2rem 0;
}

.page .wpcf7-form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
    margin-bottom: 1rem;
}

.page .wpcf7-submit {
    background: #667eea;
    color: white;
    padding: 0.75rem 2rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s ease;
}

.page .wpcf7-submit:hover {
    background: #5a6fd8;
}

/* Responsive */
@media (max-width: 768px) {
    .page .entry-title {
        font-size: 2rem;
    }
    
    .page .entry-excerpt {
        font-size: 1.1rem;
    }
    
    .page .entry-content h2 {
        font-size: 1.5rem;
    }
    
    .page .entry-content h3 {
        font-size: 1.3rem;
    }
    
    .page .entry-content ul,
    .page .entry-content ol {
        padding-left: 1.5rem;
    }
    
    .page .entry-content table {
        font-size: 0.9rem;
    }
    
    .page .entry-content th,
    .page .entry-content td {
        padding: 0.75rem 0.5rem;
    }
}

@media (max-width: 480px) {
    .page .entry-title {
        font-size: 1.5rem;
    }
    
    .page .entry-content blockquote {
        padding: 1rem;
        margin: 1rem 0;
    }
    
    .page .entry-content pre {
        padding: 1rem;
        font-size: 0.8rem;
    }
}
</style>
