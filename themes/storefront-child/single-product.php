<?php
/**
 * Template: Custom Single Product Page (Styled)
 */
defined( 'ABSPATH' ) || exit;

get_header('shop'); ?>

<div class="container my-5">
    <?php do_action( 'woocommerce_before_main_content' ); ?>

    <?php while ( have_posts() ) : the_post(); global $product; ?>

    <div class="row g-5 align-items-start">
        <!-- Hình ảnh sản phẩm -->
        <div class="col-md-6">
            <div class="product-image position-relative p-3 bg-light rounded shadow-sm">
                <?php woocommerce_show_product_sale_flash(); ?>
                <?php woocommerce_show_product_images(); ?>
            </div>
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="col-md-6">
            <h1 class="product_title h3 fw-bold mb-3"><?php the_title(); ?></h1>

            <div class="product-price h4 text-danger mb-3">
                <?php woocommerce_template_single_price(); ?>
            </div>

            <div class="product-short-desc text-muted mb-4">
                <?php woocommerce_template_single_excerpt(); ?>
            </div>

            <?php if ( ! $product->is_in_stock() ) : ?>
                <div class="alert alert-warning mt-4">
                    This product is currently out of stock and unavailable.
                </div>
            <?php endif; ?>

            <!-- Biến thể & Thêm vào giỏ -->
            <div class="product-add-to-cart border-top pt-4 mt-4">
                <?php woocommerce_template_single_add_to_cart(); ?>
            </div>
        </div>
    </div> 
    <!-- Nút đặt số lượng lớn và liên hệ -->
<div class="mt-3 d-flex flex-wrap gap-2">
    <a href="<?php echo site_url('/lien-he'); ?>" class="btn btn-outline-primary">
        Liên hệ
    </a>
    <a href="<?php echo site_url('/dat-so-luong-lon?sanpham=' . get_the_ID()); ?>" class="btn btn-warning">
        Đặt số lượng lớn
    </a>
</div>


    <!-- Tabs mô tả + đánh giá -->
    <div class="product-tabs mt-5">
        <?php woocommerce_output_product_data_tabs(); ?>
    </div>

    <!-- Sản phẩm liên quan -->
    <div class="related-products mt-5">
        <?php woocommerce_output_related_products(); ?>
    </div>

    <?php endwhile; ?>

    <?php do_action( 'woocommerce_after_main_content' ); ?>
</div>

<?php get_footer('shop'); ?>
