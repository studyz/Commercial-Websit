<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}


// Custom columns
$digitalworld_woo_lg_items = digitalworld_option('digitalworld_woo_lg_items',4);
$digitalworld_woo_md_items = digitalworld_option('digitalworld_woo_md_items',4);
$digitalworld_woo_sm_items = digitalworld_option('digitalworld_woo_sm_items',6);
$digitalworld_woo_xs_items = digitalworld_option('digitalworld_woo_xs_items',6);
$digitalworld_woo_ts_items = digitalworld_option('digitalworld_woo_ts_items',12);

$digitalworld_woo_product_style = digitalworld_option('digitalworld_woo_product_style',1);

$shop_display_mode = digitalworld_option('digitalworld_shop_display_mode','grid');

if( isset($_SESSION['shop_display_mode'])){
	$shop_display_mode = $_SESSION['shop_display_mode'];
}

$classes[] = 'product-item';
if( $shop_display_mode == "grid"){
	$classes[] = 'col-lg-'.$digitalworld_woo_lg_items;
    $classes[] = 'col-md-'.$digitalworld_woo_md_items;
    $classes[] = 'col-sm-'.$digitalworld_woo_sm_items;
    $classes[] = 'col-xs-'.$digitalworld_woo_xs_items;
    $classes[] = 'col-ts-'.$digitalworld_woo_ts_items;

}else{
	$classes[] = 'list col-sm-12';
}

$template_style = 'style-'.$digitalworld_woo_product_style;

$classes[] = 'style-'.$digitalworld_woo_product_style;
?>

<li <?php post_class( $classes ); ?>>
	<?php if( $shop_display_mode == "grid"):?>
	<?php wc_get_template_part('product-styles/content-product', $template_style );?>
	<?php else:?>
	<?php wc_get_template_part('content-product', 'list' );?>
	<?php endif;?>
</li>
