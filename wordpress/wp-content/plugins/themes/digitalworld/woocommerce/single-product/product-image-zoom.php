<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;
$digitalworld_enable_product_thumb_slide = digitalworld_option('digitalworld_enable_product_thumb_slide',1);
?>
<div class="digitalworld-product-zoom images">
	<?php
		if ( has_post_thumbnail() ) {

			$props          = wc_get_product_attachment_props( get_post_thumbnail_id(), $post );
			$image_standard = get_the_post_thumbnail( $post->ID, 'shop_single', array(
				'title'  => $props['title'],
				'alt'    => $props['alt'],
			) );

			

			$image_full            = get_the_post_thumbnail_url( $post->ID, 'full');
			$attachment_ids = $product->get_gallery_image_ids();
			?>
            <div class="digitalworld-easyzoom images images-large">
                <div class="easyzoom easyzoom--overlay easyzoom--with-thumbnails">
        			<a class="kt-zoom-main-image woocommerce-main-image zoom"  href="<?php echo esc_url($image_full);?>">
        				<?php echo $image_standard; ?>
        			</a>
                </div>
            </div>
            <div class="product_preview images-small thumbs-easyzoom">
                <?php if( $attachment_ids ):?>
    			<?php
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
        		<div class="thumbnails thumbnails_carousel digitalworld-zoom-thumbnails owl-carousel nav-center nav-style2" data-responsive='{"0":{"items":"<?php echo $digitalworld_woo_product_thumb_slide_ts_items;?>"},"480":{"items":"<?php echo $digitalworld_woo_product_thumb_slide_xs_items;?>"},"768":{"items":"<?php echo $digitalworld_woo_product_thumb_slide_sm_items;?>"},"992":{"items":"<?php echo $digitalworld_woo_product_thumb_slide_md_items;?>"},"1200":{"items":"<?php echo $digitalworld_woo_product_thumb_slide_lg_items;?>"}}' data-loop="false" data-nav="true" data-dots="false" data-margin="8">
        		<?php else:?>
        		<div class="thumbnails <?php echo 'columns-' . $columns; ?>">
        		<?php endif;?>
        				<?php foreach ( $attachment_ids as $attachment_id ):?>
        				<?php 
        				$image_thumb = wp_get_attachment_image( $attachment_id, 'shop_thumbnail', array(
        					'title'	 => $props['title'],
        					'alt'    => $props['alt'],
        				) );
        
        				$image_full     = wp_get_attachment_image_url( $attachment_id, 'full');
        				$image_standard = wp_get_attachment_image_url( $attachment_id, 'shop_single');
        				?>
        				<a class="zoom-thumb-link" href="<?php echo esc_url($image_full); ?>" data-standard="<?php echo esc_url( $image_standard );?>">
        					<?php echo $image_thumb;?>
        				</a>
        				<?php endforeach;?>
        			</div>
     			<?php endif;?>
            </div>
			<?php

		}
	?>
</div>