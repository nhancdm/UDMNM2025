<?php
/**
 * The sidebar containing the main widget area
 *
 * @package Nhan
 */

if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>

<aside id="secondary" class="widget-area">
    <?php dynamic_sidebar('sidebar-1'); ?>
    
    <?php if (!is_active_sidebar('sidebar-1')) : ?>
        <!-- Default widgets when no widgets are added -->
        <section class="widget widget_search">
            <h3 class="widget-title"><?php esc_html_e('Search', 'nhan'); ?></h3>
            <?php get_search_form(); ?>
        </section>

        <section class="widget widget_recent_entries">
            <h3 class="widget-title"><?php esc_html_e('Recent Posts', 'nhan'); ?></h3>
            <ul>
                <?php
                $recent_posts = wp_get_recent_posts(array(
                    'numberposts' => 5,
                    'post_status' => 'publish'
                ));
                
                foreach ($recent_posts as $post) :
                ?>
                    <li>
                        <a href="<?php echo esc_url(get_permalink($post['ID'])); ?>">
                            <?php echo esc_html($post['post_title']); ?>
                        </a>
                        <span class="post-date"><?php echo esc_html(get_the_date('', $post['ID'])); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="widget widget_categories">
            <h3 class="widget-title"><?php esc_html_e('Categories', 'nhan'); ?></h3>
            <ul>
                <?php
                wp_list_categories(array(
                    'orderby'    => 'count',
                    'order'      => 'DESC',
                    'show_count' => 1,
                    'title_li'   => '',
                    'number'     => 10,
                ));
                ?>
            </ul>
        </section>

        <section class="widget widget_tag_cloud">
            <h3 class="widget-title"><?php esc_html_e('Tags', 'nhan'); ?></h3>
            <?php
            wp_tag_cloud(array(
                'smallest' => 0.8,
                'largest'  => 1.2,
                'unit'     => 'rem',
                'number'   => 20,
            ));
            ?>
        </section>

        <section class="widget widget_archive">
            <h3 class="widget-title"><?php esc_html_e('Archives', 'nhan'); ?></h3>
            <ul>
                <?php
                wp_get_archives(array(
                    'type'  => 'monthly',
                    'limit' => 12,
                ));
                ?>
            </ul>
        </section>

        <section class="widget widget_meta">
            <h3 class="widget-title"><?php esc_html_e('Meta', 'nhan'); ?></h3>
            <ul>
                <?php wp_register(); ?>
                <li><?php wp_loginout(); ?></li>
                <li><a href="<?php echo esc_url(get_bloginfo('rss2_url')); ?>"><?php esc_html_e('Entries RSS', 'nhan'); ?></a></li>
                <li><a href="<?php echo esc_url(get_bloginfo('comments_rss2_url')); ?>"><?php esc_html_e('Comments RSS', 'nhan'); ?></a></li>
                <li><a href="https://wordpress.org/"><?php esc_html_e('WordPress.org', 'nhan'); ?></a></li>
            </ul>
        </section>
    <?php endif; ?>
</aside><!-- #secondary -->

<style>
/* Additional sidebar styles */
.widget .post-date {
    display: block;
    font-size: 0.8rem;
    color: #666;
    margin-top: 0.25rem;
}

.widget_tag_cloud .tagcloud {
    line-height: 1.8;
}

.widget_tag_cloud .tagcloud a {
    display: inline-block;
    margin: 0.25rem 0.5rem 0.25rem 0;
    padding: 0.25rem 0.75rem;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 20px;
    text-decoration: none;
    color: #495057;
    transition: all 0.3s ease;
}

.widget_tag_cloud .tagcloud a:hover {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.widget_categories ul li,
.widget_archive ul li,
.widget_recent_entries ul li,
.widget_meta ul li {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.widget_categories .cat-item-count {
    background: #667eea;
    color: white;
    padding: 0.2rem 0.5rem;
    border-radius: 10px;
    font-size: 0.8rem;
    margin-left: 0.5rem;
}

/* Search form in sidebar */
.widget_search .search-form {
    position: relative;
}

.widget_search .search-field {
    width: 100%;
    padding-right: 50px;
}

.widget_search .search-submit {
    position: absolute;
    right: 0;
    top: 0;
    height: 100%;
    padding: 0 1rem;
    background: #667eea;
    border: none;
    border-radius: 0 4px 4px 0;
    color: white;
    cursor: pointer;
}

.widget_search .search-submit:hover {
    background: #5a6fd8;
}

/* Responsive sidebar */
@media (max-width: 768px) {
    .widget-area {
        margin-top: 2rem;
    }
}
</style>
