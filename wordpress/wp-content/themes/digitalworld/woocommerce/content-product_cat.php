<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product_cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Custom columns
$digitalworld_woo_lg_items = digitalworld_option('digitalworld_woo_lg_items',4);
$digitalworld_woo_md_items = digitalworld_option('digitalworld_woo_md_items',4);
$digitalworld_woo_sm_items = digitalworld_option('digitalworld_woo_sm_items',6);
$digitalworld_woo_xs_items = digitalworld_option('digitalworld_woo_xs_items',6);
$digitalworld_woo_ts_items = digitalworld_option('digitalworld_woo_ts_items',12);

$classes[] = 'col-lg-'.$digitalworld_woo_lg_items;
$classes[] = 'col-md-'.$digitalworld_woo_md_items;
$classes[] = 'col-sm-'.$digitalworld_woo_sm_items;
$classes[] = 'col-xs-'.$digitalworld_woo_xs_items;
$classes[] = 'col-ts-'.$digitalworld_woo_ts_items;


?>
<li <?php wc_product_cat_class( $classes, $category ); ?>>
	<?php
	/**
	 * woocommerce_before_subcategory hook.
	 *
	 * @hooked woocommerce_template_loop_category_link_open - 10
	 */
	do_action( 'woocommerce_before_subcategory', $category );

	/**
	 * woocommerce_before_subcategory_title hook.
	 *
	 * @hooked woocommerce_subcategory_thumbnail - 10
	 */
	do_action( 'woocommerce_before_subcategory_title', $category );

	/**
	 * woocommerce_shop_loop_subcategory_title hook.
	 *
	 * @hooked woocommerce_template_loop_category_title - 10
	 */
	do_action( 'woocommerce_shop_loop_subcategory_title', $category );

	/**
	 * woocommerce_after_subcategory_title hook.
	 */
	do_action( 'woocommerce_after_subcategory_title', $category );

	/**
	 * woocommerce_after_subcategory hook.
	 *
	 * @hooked woocommerce_template_loop_category_link_close - 10
	 */
	do_action( 'woocommerce_after_subcategory', $category ); ?>
</li>
