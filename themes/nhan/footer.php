<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package Nhan
 */
?>

    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3')) : ?>
            <div class="footer-widgets">
                <div class="container">
                    <div class="footer-widget-area">
                        <?php if (is_active_sidebar('footer-1')) : ?>
                            <div class="footer-widget-column">
                                <?php dynamic_sidebar('footer-1'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (is_active_sidebar('footer-2')) : ?>
                            <div class="footer-widget-column">
                                <?php dynamic_sidebar('footer-2'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (is_active_sidebar('footer-3')) : ?>
                            <div class="footer-widget-column">
                                <?php dynamic_sidebar('footer-3'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="site-info">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-text">
                        <?php
                        $footer_text = get_theme_mod('nhan_footer_text', __('© 2024 Nhan Theme. All rights reserved.', 'nhan'));
                        echo wp_kses_post($footer_text);
                        ?>
                    </div>

                    <?php
                    $social_links = nhan_get_social_links();
                    if ($social_links) :
                    ?>
                        <div class="social-links">
                            <?php echo $social_links; ?>
                        </div>
                    <?php endif; ?>

                    <?php
                    // Footer navigation menu
                    if (has_nav_menu('footer')) :
                    ?>
                        <nav class="footer-navigation">
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'footer',
                                'menu_id'        => 'footer-menu',
                                'container'      => false,
                                'depth'          => 1,
                            ));
                            ?>
                        </nav>
                    <?php endif; ?>
                </div>

                <div class="footer-bottom">
                    <p>
                        <?php
                        printf(
                            esc_html__('Powered by %1$s | Theme: %2$s', 'nhan'),
                            '<a href="' . esc_url(__('https://wordpress.org/', 'nhan')) . '">WordPress</a>',
                            '<a href="' . esc_url('https://github.com/nhan') . '">Nhan</a>'
                        );
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </footer><!-- #colophon -->

    <!-- Back to top button -->
    <button id="back-to-top" class="back-to-top" aria-label="<?php esc_attr_e('Back to top', 'nhan'); ?>">
        <i class="fas fa-chevron-up"></i>
    </button>

</div><!-- #page -->

<?php wp_footer(); ?>

<style>
/* Footer Styles */
.footer-widgets {
    background: #34495e;
    color: white;
    padding: 3rem 0;
}

.footer-widget-area {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.footer-widget-column .widget {
    background: transparent;
    box-shadow: none;
    padding: 0;
}

.footer-widget-column .widget-title {
    color: white;
    border-bottom-color: #667eea;
}

.footer-widget-column .widget ul li a {
    color: #bdc3c7;
}

.footer-widget-column .widget ul li a:hover {
    color: white;
}

.site-info {
    background: #2c3e50;
    color: white;
    padding: 2rem 0 1rem;
}

.footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1rem;
}

.footer-text {
    flex: 1;
}

.social-links {
    display: flex;
    gap: 1rem;
}

.social-links a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: #667eea;
    color: white;
    border-radius: 50%;
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-links a:hover {
    background: #5a6fd8;
    transform: translateY(-2px);
}

.footer-navigation ul {
    list-style: none;
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.footer-navigation a {
    color: #bdc3c7;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-navigation a:hover {
    color: white;
}

.footer-bottom {
    text-align: center;
    padding-top: 1rem;
    border-top: 1px solid #34495e;
    font-size: 0.9rem;
    color: #bdc3c7;
}

.footer-bottom a {
    color: #667eea;
    text-decoration: none;
}

.footer-bottom a:hover {
    text-decoration: underline;
}

/* Back to top button */
.back-to-top {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 50px;
    height: 50px;
    background: #667eea;
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: none;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    transition: all 0.3s ease;
    z-index: 1000;
}

.back-to-top:hover {
    background: #5a6fd8;
    transform: translateY(-2px);
}

.back-to-top.show {
    display: flex;
}

/* Responsive */
@media (max-width: 768px) {
    .footer-content {
        flex-direction: column;
        text-align: center;
    }
    
    .footer-navigation ul {
        justify-content: center;
    }
    
    .social-links {
        justify-content: center;
    }
}
</style>

<script>
// Back to top functionality
document.addEventListener('DOMContentLoaded', function() {
    const backToTopButton = document.getElementById('back-to-top');
    
    if (backToTopButton) {
        // Show/hide button based on scroll position
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('show');
            } else {
                backToTopButton.classList.remove('show');
            }
        });
        
        // Smooth scroll to top
        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
});
</script>

</body>
</html>
