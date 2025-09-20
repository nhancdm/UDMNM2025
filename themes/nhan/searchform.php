<?php
/**
 * Template for displaying search forms
 *
 * @package Nhan
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label>
        <span class="screen-reader-text"><?php echo _x('Search for:', 'label', 'nhan'); ?></span>
        <input type="search" 
               class="search-field" 
               placeholder="<?php echo esc_attr( get_theme_mod('nhan_search_placeholder', __('Bạn muốn đi du lịch ở đâu?', 'nhan')) ); ?>" 
               value="<?php echo get_search_query(); ?>" 
               name="s" 
               title="<?php echo esc_attr_x('Search for:', 'label', 'nhan'); ?>" />
    </label>
    <input type="submit" class="search-submit" value="<?php echo esc_attr_x('Search', 'submit button', 'nhan'); ?>" />
</form>

<style>
.search-form {
    position: relative;
    display: flex;
    max-width: 400px;
    margin: 0 auto;
}

.search-form label {
    flex: 1;
    position: relative;
}

.search-field {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #ddd;
    border-radius: 25px 0 0 25px;
    font-size: 1rem;
    outline: none;
    transition: border-color 0.3s ease;
}

.search-field:focus {
    border-color: #667eea;
}

.search-submit {
    padding: 0.75rem 1.5rem;
    background: #667eea;
    color: white;
    border: 2px solid #667eea;
    border-left: none;
    border-radius: 0 25px 25px 0;
    cursor: pointer;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.search-submit:hover {
    background: #5a6fd8;
    border-color: #5a6fd8;
}

.screen-reader-text {
    clip: rect(1px, 1px, 1px, 1px);
    position: absolute !important;
    height: 1px;
    width: 1px;
    overflow: hidden;
}

/* Header search form variant */
.header-search .search-form {
    max-width: 300px;
}

.header-search .search-field {
    background: rgba(255,255,255,0.9);
    border-color: rgba(255,255,255,0.3);
}

.header-search .search-field:focus {
    background: white;
    border-color: white;
}

.header-search .search-submit {
    background: rgba(255,255,255,0.2);
    border-color: rgba(255,255,255,0.3);
}

.header-search .search-submit:hover {
    background: rgba(255,255,255,0.3);
}

/* Widget search form variant */
.widget .search-form {
    max-width: none;
}

.widget .search-field {
    border-radius: 4px 0 0 4px;
}

.widget .search-submit {
    border-radius: 0 4px 4px 0;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .search-form {
        max-width: 100%;
    }
    
    .search-field {
        font-size: 16px; /* Prevents zoom on iOS */
    }
}
</style>
