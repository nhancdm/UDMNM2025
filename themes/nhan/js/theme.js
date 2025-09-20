/**
 * Nhan Theme JavaScript
 * 
 * @package Nhan
 */

(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {
        
        // Mobile menu toggle
        initMobileMenu();
        
        // Search toggle
        initSearchToggle();
        
        // Smooth scrolling for anchor links
        initSmoothScrolling();
        
        // Back to top button
        initBackToTop();
        
        // Image lazy loading
        initLazyLoading();
        
        // Comment form enhancements
        initCommentForm();
        
        // Accessibility improvements
        initAccessibility();
        
        // External links
        initExternalLinks();
    });

    // Window load
    $(window).on('load', function() {
        // Remove loading class if exists
        $('body').removeClass('loading');
        
        // Initialize masonry if needed
        initMasonry();
    });

    // Window scroll
    $(window).on('scroll', function() {
        handleScroll();
    });

    // Window resize
    $(window).on('resize', function() {
        handleResize();
    });

    /**
     * Mobile Menu Toggle
     */
    function initMobileMenu() {
        const menuToggle = $('.menu-toggle');
        const navigation = $('.main-navigation');
        const menu = $('#primary-menu');

        menuToggle.on('click', function(e) {
            e.preventDefault();
            
            const isExpanded = $(this).attr('aria-expanded') === 'true';
            
            $(this).attr('aria-expanded', !isExpanded);
            navigation.toggleClass('toggled');
            menu.slideToggle(300);
            
            // Toggle hamburger animation
            $(this).toggleClass('active');
        });

        // Close menu when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.main-navigation').length) {
                menuToggle.attr('aria-expanded', 'false');
                navigation.removeClass('toggled');
                menu.slideUp(300);
                menuToggle.removeClass('active');
            }
        });

        // Handle submenu toggles on mobile
        menu.find('.menu-item-has-children > a').on('click', function(e) {
            if ($(window).width() <= 768) {
                e.preventDefault();
                $(this).next('.sub-menu').slideToggle(200);
                $(this).parent().toggleClass('submenu-open');
            }
        });
    }

    /**
     * Search Toggle
     */
    function initSearchToggle() {
        const searchToggle = $('.search-toggle');
        const searchContainer = $('.search-form-container');

        searchToggle.on('click', function(e) {
            e.preventDefault();
            
            const isExpanded = $(this).attr('aria-expanded') === 'true';
            
            $(this).attr('aria-expanded', !isExpanded);
            searchContainer.toggleClass('active');
            
            if (!isExpanded) {
                searchContainer.find('.search-field').focus();
            }
        });

        // Close search when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.header-search').length) {
                searchToggle.attr('aria-expanded', 'false');
                searchContainer.removeClass('active');
            }
        });
    }

    /**
     * Smooth Scrolling
     */
    function initSmoothScrolling() {
        $('a[href*="#"]:not([href="#"])').on('click', function(e) {
            if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && 
                location.hostname === this.hostname) {
                
                const target = $(this.hash);
                const targetElement = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                
                if (targetElement.length) {
                    e.preventDefault();
                    
                    $('html, body').animate({
                        scrollTop: targetElement.offset().top - 100
                    }, 800, 'swing');
                }
            }
        });
    }

    /**
     * Back to Top Button
     */
    function initBackToTop() {
        const backToTop = $('#back-to-top');
        
        if (backToTop.length) {
            backToTop.on('click', function(e) {
                e.preventDefault();
                
                $('html, body').animate({
                    scrollTop: 0
                }, 800, 'swing');
            });
        }
    }

    /**
     * Image Lazy Loading (fallback for older browsers)
     */
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }

    /**
     * Comment Form Enhancements
     */
    function initCommentForm() {
        const commentForm = $('#commentform');
        
        if (commentForm.length) {
            // Add character counter for comment textarea
            const commentTextarea = commentForm.find('#comment');
            if (commentTextarea.length) {
                const maxLength = 1000;
                const counter = $('<div class="comment-counter"></div>');
                commentTextarea.after(counter);
                
                commentTextarea.on('input', function() {
                    const remaining = maxLength - $(this).val().length;
                    counter.text(remaining + ' characters remaining');
                    
                    if (remaining < 50) {
                        counter.addClass('warning');
                    } else {
                        counter.removeClass('warning');
                    }
                });
            }

            // Form validation
            commentForm.on('submit', function(e) {
                const requiredFields = $(this).find('[required]');
                let isValid = true;

                requiredFields.each(function() {
                    const field = $(this);
                    const value = field.val().trim();
                    
                    if (!value) {
                        field.addClass('error');
                        isValid = false;
                    } else {
                        field.removeClass('error');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Please fill in all required fields.');
                }
            });
        }
    }

    /**
     * Accessibility Improvements
     */
    function initAccessibility() {
        // Skip link focus fix
        $('.skip-link').on('click', function() {
            const target = $($(this).attr('href'));
            if (target.length) {
                target.attr('tabindex', '-1').focus();
            }
        });

        // Keyboard navigation for dropdowns
        $('.main-navigation').on('keydown', 'a', function(e) {
            const link = $(this);
            const submenu = link.next('.sub-menu');

            if (e.keyCode === 13 || e.keyCode === 32) { // Enter or Space
                if (submenu.length) {
                    e.preventDefault();
                    submenu.toggleClass('focus');
                }
            }

            if (e.keyCode === 27) { // Escape
                submenu.removeClass('focus');
                link.focus();
            }
        });

        // Focus management for modals/popups
        $(document).on('keydown', function(e) {
            if (e.keyCode === 27) { // Escape key
                $('.search-form-container.active').removeClass('active');
                $('.main-navigation.toggled').removeClass('toggled');
            }
        });
    }

    /**
     * External Links
     */
    function initExternalLinks() {
        $('a[href^="http"]:not([href*="' + location.hostname + '"])').each(function() {
            $(this).attr({
                target: '_blank',
                rel: 'noopener noreferrer'
            });
            
            // Add external link icon
            if (!$(this).find('.external-icon').length) {
                $(this).append(' <i class="fas fa-external-link-alt external-icon"></i>');
            }
        });
    }

    /**
     * Masonry Layout (for blog grid if implemented)
     */
    function initMasonry() {
        if (typeof $.fn.masonry !== 'undefined') {
            $('.masonry-container').masonry({
                itemSelector: '.masonry-item',
                columnWidth: '.masonry-sizer',
                percentPosition: true
            });
        }
    }

    /**
     * Handle Scroll Events
     */
    function handleScroll() {
        const scrollTop = $(window).scrollTop();
        const backToTop = $('#back-to-top');

        // Show/hide back to top button
        if (scrollTop > 300) {
            backToTop.addClass('show');
        } else {
            backToTop.removeClass('show');
        }

        // Header scroll effect
        const header = $('.site-header');
        if (scrollTop > 100) {
            header.addClass('scrolled');
        } else {
            header.removeClass('scrolled');
        }

        // Parallax effect for hero sections
        $('.parallax-section').each(function() {
            const parallaxElement = $(this);
            const speed = parallaxElement.data('speed') || 0.5;
            const yPos = -(scrollTop * speed);
            parallaxElement.css('transform', 'translateY(' + yPos + 'px)');
        });
    }

    /**
     * Handle Resize Events
     */
    function handleResize() {
        const windowWidth = $(window).width();

        // Close mobile menu on resize to desktop
        if (windowWidth > 768) {
            $('.main-navigation').removeClass('toggled');
            $('.menu-toggle').attr('aria-expanded', 'false').removeClass('active');
            $('#primary-menu').removeAttr('style');
        }

        // Reinitialize masonry on resize
        if (typeof $.fn.masonry !== 'undefined') {
            $('.masonry-container').masonry('layout');
        }
    }

    /**
     * Utility Functions
     */
    
    // Debounce function
    function debounce(func, wait, immediate) {
        let timeout;
        return function() {
            const context = this;
            const args = arguments;
            const later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }

    // Throttle function
    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    // Use throttled scroll handler
    $(window).on('scroll', throttle(handleScroll, 16));

})(jQuery);

// Vanilla JavaScript for critical functionality
document.addEventListener('DOMContentLoaded', function() {
    
    // Add loading class to body
    document.body.classList.add('loaded');
    
    // Initialize critical features that don't require jQuery
    initCriticalFeatures();
    
    function initCriticalFeatures() {
        // Service Worker registration (if needed)
        if ('serviceWorker' in navigator) {
            // Uncomment if you want to add PWA features
            // navigator.serviceWorker.register('/sw.js');
        }
        
        // Critical CSS loading
        loadCriticalCSS();
        
        // Preload important resources
        preloadResources();
    }
    
    function loadCriticalCSS() {
        // Load non-critical CSS asynchronously
        const nonCriticalCSS = [
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'
        ];
        
        nonCriticalCSS.forEach(function(href) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = href;
            link.media = 'print';
            link.onload = function() {
                this.media = 'all';
            };
            document.head.appendChild(link);
        });
    }
    
    function preloadResources() {
        // Preload important images or fonts
        const importantResources = [
            // Add important resource URLs here
        ];
        
        importantResources.forEach(function(src) {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.as = 'image';
            link.href = src;
            document.head.appendChild(link);
        });
    }
});
