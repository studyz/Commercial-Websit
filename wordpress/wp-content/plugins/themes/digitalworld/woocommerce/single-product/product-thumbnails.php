<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product, $woocommerce;

$attachment_ids = $product->get_gallery_image_ids();
$digitalworld_enable_product_thumb_slide = digitalworld_option('digitalworld_enable_product_thumb_slide',1);

if ( $attachment_ids ) {
	$loop 		= 0;
	$columns 	= apply_filters( 'woocommerce_product_thumbnails_columns', 3 );
	?>
	<?php if( $digitalworld_enable_product_thumb_slide == 1):?>
		<?php
		$digitalworld_woo_product_thumb_slide_lg_items = digitalworld_option('digitalworld_woo_product_thumb_slide_lg_items',4);
		$digitalworld_woo_product_thumb_slide_md_items = digitalworld_option('digitalworld_woo_product_thumb_slide_md_items',4);
		$digitalworld_woo_product_thumb_slide_sm_items = digitalworld_option('digitalworld_woo_product_thumb_slide_sm_items',3);
		$digitalworld_woo_product_thumb_slide_xs_items = digitalworld_option('digitalworld_woo_product_thumb_slide_xs_items',2);
		$digitalworld_woo_product_thumb_slide_ts_items = digitalworld_option('digitalworld_woo_product_thumb_slide_ts_items',1);
		?>
		<div class="owl-carousel thumbnails_carousel nav-center nav-style2" data-responsive='{"0":{"items":"<?php echo $digitalworld_woo_product_thumb_slide_ts_items;?>"},"480":{"items":"<?php echo $digitalworld_woo_product_thumb_slide_xs_items;?>"},"768":{"items":"<?php echo $digitalworld_woo_product_thumb_slide_sm_items;?>"},"992":{"items":"<?php echo $digitalworld_woo_product_thumb_slide_md_items;?>"},"1200":{"items":"<?php echo $digitalworld_woo_product_thumb_slide_lg_items;?>"}}' data-loop="false" data-nav="true" data-dots="false" data-margin="8">
	<?php else:?>
		<div class="thumbnails <?php echo 'columns-' . $columns; ?>">
	<?php endif;?>
	<?php

		foreach ( $attachment_ids as $attachment_id ) {

			$classes = array( 'zoom' );

			if ( $loop === 0 || $loop % $columns === 0 ) {
				$classes[] = 'first';
			}

			if ( ( $loop + 1 ) % $columns === 0 ) {
				$classes[] = 'last';
			}

			$image_class = implode( ' ', $classes );
			$props       = wc_get_product_attachment_props( $attachment_id, $post );

			if ( ! $props['url'] ) {
				continue;
			}

			echo apply_filters(
				'woocommerce_single_product_image_thumbnail_html',
				sprintf(
					'<a href="%s" class="%s html5lightbox" data-group="woocommerce_single_product_image" title="%s" data-rel="prettyPhoto[product-gallery]">%s</a>',
					esc_url( $props['url'] ),
					esc_attr( $image_class ),
					esc_attr( $props['caption'] ),
					wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ), 0, $props )
				),
				$attachment_id,
				$post->ID,
				esc_attr( $image_class )
			);

			$loop++;
		}

	?></div>
	<?php
}
