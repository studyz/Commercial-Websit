<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php

$digitalworld_woo_single_layout = digitalworld_option('digitalworld_woo_single_layout','left');
$single_col_left = 'col-sm-12 col-md-6 col-lg-6';
$single_col_right = 'col-sm-12 col-md-6 col-lg-6';

if( $digitalworld_woo_single_layout == "full"){
	$single_col_left = 'col-sm-6 col-md-4 col-lg-4';
	$single_col_right = 'col-sm-6 col-md-8 col-lg-7 col-lg-offset-1';
}
?>

<?php
	/**
	 * woocommerce_before_single_product hook.
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>
<div class="catalog-product-view catalog-view_default">
    <div  id="product-<?php the_ID(); ?>" <?php post_class('single-product'); ?>>
        <div class="row">
            <div class="<?php echo esc_attr($single_col_left)?>">
                <div class="single-left">
                    <?php
                    /**
                     * woocommerce_before_single_product_summary hook.
                     *
                     * @hooked woocommerce_show_product_sale_flash - 10
                     * @hooked woocommerce_show_product_images - 20
                     */
                    do_action( 'woocommerce_before_single_product_summary' );
                    ?>
                </div>
            </div>
            <div class="<?php echo esc_attr($single_col_right)?>">
                <div class="summary">
                    <?php
    				/**
    				 * woocommerce_single_product_summary hook.
    				 *
    				 * @hooked woocommerce_template_single_title - 5
    				 * @hooked woocommerce_template_single_rating - 10
    				 * @hooked woocommerce_template_single_price - 10
    				 * @hooked woocommerce_template_single_excerpt - 20
    				 * @hooked woocommerce_template_single_add_to_cart - 30
    				 * @hooked woocommerce_template_single_meta - 40
    				 * @hooked woocommerce_template_single_sharing - 50
    				 */
    				do_action( 'woocommerce_single_product_summary' );
    				?>
                </div>
            </div>
        </div>
    </div><!-- #product-<?php the_ID(); ?> -->
    <?php
		/**
		 * woocommerce_after_single_product_summary hook.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
	?>

	<meta itemprop="url" content="<?php the_permalink(); ?>" />
    <?php do_action( 'woocommerce_after_single_product' ); ?>
</div>
