<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Nhan
 */

get_header();
?>

<div class="container">
    <main class="site-main">
        <div class="error-404 not-found">
            <div class="error-content">
                <div class="error-illustration">
                    <div class="error-number">404</div>
                    <div class="error-icon">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                
                <header class="page-header">
                    <h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'nhan'); ?></h1>
                </header>

                <div class="page-content">
                    <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'nhan'); ?></p>

                    <div class="error-search">
                        <?php get_search_form(); ?>
                    </div>

                    <div class="error-suggestions">
                        <div class="suggestion-section">
                            <h3><?php esc_html_e('Popular Pages', 'nhan'); ?></h3>
                            <ul>
                                <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'nhan'); ?></a></li>
                                <?php
                                // Get popular pages
                                $popular_pages = get_pages(array(
                                    'sort_column' => 'menu_order',
                                    'number' => 5
                                ));
                                
                                foreach ($popular_pages as $page) {
                                    echo '<li><a href="' . esc_url(get_permalink($page->ID)) . '">' . esc_html($page->post_title) . '</a></li>';
                                }
                                ?>
                            </ul>
                        </div>

                        <div class="suggestion-section">
                            <h3><?php esc_html_e('Recent Posts', 'nhan'); ?></h3>
                            <ul>
                                <?php
                                $recent_posts = wp_get_recent_posts(array(
                                    'numberposts' => 5,
                                    'post_status' => 'publish'
                                ));
                                
                                foreach ($recent_posts as $post) {
                                    echo '<li><a href="' . esc_url(get_permalink($post['ID'])) . '">' . esc_html($post['post_title']) . '</a></li>';
                                }
                                ?>
                            </ul>
                        </div>

                        <div class="suggestion-section">
                            <h3><?php esc_html_e('Categories', 'nhan'); ?></h3>
                            <ul>
                                <?php
                                wp_list_categories(array(
                                    'orderby'    => 'count',
                                    'order'      => 'DESC',
                                    'show_count' => 1,
                                    'title_li'   => '',
                                    'number'     => 5,
                                ));
                                ?>
                            </ul>
                        </div>
                    </div>

                    <div class="error-actions">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                            <i class="fas fa-home"></i>
                            <?php esc_html_e('Go to Homepage', 'nhan'); ?>
                        </a>
                        
                        <button onclick="history.back()" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            <?php esc_html_e('Go Back', 'nhan'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php get_footer(); ?>

<style>
/* 404 Error Page Styles */
.error-404 {
    text-align: center;
    padding: 4rem 2rem;
    min-height: 60vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.error-content {
    max-width: 800px;
    width: 100%;
}

.error-illustration {
    position: relative;
    margin-bottom: 3rem;
}

.error-number {
    font-size: 8rem;
    font-weight: 900;
    color: #667eea;
    line-height: 1;
    margin-bottom: 1rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.error-icon {
    font-size: 3rem;
    color: #bdc3c7;
    margin-bottom: 2rem;
}

.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2.5rem;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.page-content > p {
    font-size: 1.2rem;
    color: #666;
    margin-bottom: 3rem;
    line-height: 1.6;
}

.error-search {
    margin-bottom: 4rem;
}

.error-search .search-form {
    max-width: 500px;
    margin: 0 auto;
}

.error-suggestions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-bottom: 4rem;
    text-align: left;
}

.suggestion-section {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.suggestion-section h3 {
    color: #2c3e50;
    margin-bottom: 1rem;
    font-size: 1.3rem;
    border-bottom: 2px solid #667eea;
    padding-bottom: 0.5rem;
}

.suggestion-section ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.suggestion-section ul li {
    padding: 0.5rem 0;
    border-bottom: 1px solid #eee;
}

.suggestion-section ul li:last-child {
    border-bottom: none;
}

.suggestion-section ul li a {
    color: #333;
    text-decoration: none;
    transition: color 0.3s ease;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.suggestion-section ul li a:hover {
    color: #667eea;
}

.suggestion-section .cat-item-count {
    background: #667eea;
    color: white;
    padding: 0.2rem 0.5rem;
    border-radius: 10px;
    font-size: 0.8rem;
}

.error-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 2rem;
    border: none;
    border-radius: 6px;
    text-decoration: none;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-primary:hover {
    background: #5a6fd8;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
}

/* Animation for error number */
@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

.error-number {
    animation: bounce 2s infinite;
}

/* Responsive Design */
@media (max-width: 768px) {
    .error-404 {
        padding: 2rem 1rem;
    }
    
    .error-number {
        font-size: 6rem;
    }
    
    .page-title {
        font-size: 2rem;
    }
    
    .page-content > p {
        font-size: 1.1rem;
    }
    
    .error-suggestions {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .suggestion-section {
        padding: 1.5rem;
    }
    
    .error-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn {
        width: 100%;
        max-width: 300px;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .error-number {
        font-size: 4rem;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .error-icon {
        font-size: 2rem;
    }
    
    .suggestion-section {
        padding: 1rem;
    }
}

/* Dark mode support (if implemented) */
@media (prefers-color-scheme: dark) {
    .error-404 {
        color: #ecf0f1;
    }
    
    .page-title {
        color: #ecf0f1;
    }
    
    .suggestion-section {
        background: #34495e;
        color: #ecf0f1;
    }
    
    .suggestion-section h3 {
        color: #ecf0f1;
    }
    
    .suggestion-section ul li a {
        color: #bdc3c7;
    }
    
    .suggestion-section ul li a:hover {
        color: #667eea;
    }
}

/* Print styles */
@media print {
    .error-actions,
    .error-search {
        display: none;
    }
    
    .error-404 {
        padding: 2rem 0;
    }
    
    .error-number {
        color: #333 !important;
        -webkit-text-fill-color: #333 !important;
    }
}
</style>

<script>
// Add some interactive elements
document.addEventListener('DOMContentLoaded', function() {
    // Add floating animation to error icon
    const errorIcon = document.querySelector('.error-icon i');
    if (errorIcon) {
        setInterval(function() {
            errorIcon.style.transform = 'translateY(-5px)';
            setTimeout(function() {
                errorIcon.style.transform = 'translateY(0)';
            }, 1000);
        }, 2000);
    }
    
    // Track 404 errors (if analytics is available)
    if (typeof gtag !== 'undefined') {
        gtag('event', 'page_view', {
            page_title: '404 Error',
            page_location: window.location.href
        });
    }
});
</script>
