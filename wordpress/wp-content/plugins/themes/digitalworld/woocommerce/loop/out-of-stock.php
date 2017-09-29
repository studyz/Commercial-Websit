<?php
/**
 * Product loop Out of Stock flash
 *
 * @author 		Kutethemes
 * @package 	digitalworld
 * @version     1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $post, $product;
?>
<?php if ( !$product->is_in_stock()) : ?>
    <?php echo apply_filters( 'woocommerce_out_of_stock_flash', '<span class="out-of-stock"><span class="text">' . esc_html__( 'Sold Out', 'digitalworld' ) . '</span></span>', $post, $product ); ?>
<?php endif; ?>

