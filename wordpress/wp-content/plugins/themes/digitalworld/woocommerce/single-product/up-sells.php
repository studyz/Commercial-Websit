<?php
/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
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
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$digitalworld_woo_upsell_lg_items = digitalworld_option('digitalworld_woo_upsell_lg_items',4);
$digitalworld_woo_upsell_md_items = digitalworld_option('digitalworld_woo_upsell_md_items',4);
$digitalworld_woo_upsell_sm_items = digitalworld_option('digitalworld_woo_upsell_sm_items',3);
$digitalworld_woo_upsell_xs_items = digitalworld_option('digitalworld_woo_upsell_xs_items',2);
$digitalworld_woo_upsell_ts_items = digitalworld_option('digitalworld_woo_upsell_ts_items',1);

$data_reponsive = array(
	'0'=>array(
		'items'=>$digitalworld_woo_upsell_ts_items
	),
	'480'=>array(
		'items'=>$digitalworld_woo_upsell_xs_items
	),
	'768'=>array(
		'items'=>$digitalworld_woo_upsell_sm_items
	),
	'992'=>array(
		'items'=>$digitalworld_woo_upsell_md_items
	),
	'1200'=>array(
		'items'=>$digitalworld_woo_upsell_lg_items
	),
);

$data_reponsive = json_encode($data_reponsive);
$loop = 'false';
$digitalworld_upsell_products_title = digitalworld_option('digitalworld_upsell_products_title','You may also like');

if ( $upsells ) : ?>
	<div class="block-upsell block-related">
		<h3 class="block-title">
			<?php echo esc_html($digitalworld_upsell_products_title);?>
		</h3>
		<div class="block-content ">
			<ul class="product-items owl-carousel kt-owl-carousel equal-container" data-nav="true" data-margin="28" data-loop="<?php echo esc_attr($loop);?>" data-responsive='<?php echo esc_attr($data_reponsive);?>'>
				<?php foreach ( $upsells as $upsell ) : ?>

					<?php
					$post_object = get_post( $upsell->get_id() );

					setup_postdata( $GLOBALS['post'] =& $post_object );

					wc_get_template_part( 'content', 'product-upsell' );
					?>

				<?php endforeach; ?>
			</ul>
		</div>
	</div>

<?php endif;

wp_reset_postdata();

