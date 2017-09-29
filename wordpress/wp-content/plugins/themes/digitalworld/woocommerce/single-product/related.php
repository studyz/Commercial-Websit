<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
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
$digitalworld_woo_related_lg_items = digitalworld_option('digitalworld_woo_related_lg_items',4);
$digitalworld_woo_related_md_items = digitalworld_option('digitalworld_woo_related_md_items',4);
$digitalworld_woo_related_sm_items = digitalworld_option('digitalworld_woo_related_sm_items',3);
$digitalworld_woo_related_xs_items = digitalworld_option('digitalworld_woo_related_xs_items',2);
$digitalworld_woo_related_ts_items = digitalworld_option('digitalworld_woo_related_ts_items',1);

$data_reponsive = array(
	'0'=>array(
		'items'=>$digitalworld_woo_related_ts_items
	),
	'480'=>array(
		'items'=>$digitalworld_woo_related_xs_items
	),
	'768'=>array(
		'items'=>$digitalworld_woo_related_sm_items
	),
	'992'=>array(
		'items'=>$digitalworld_woo_related_md_items
	),
	'1200'=>array(
		'items'=>$digitalworld_woo_related_lg_items
	),
);
$data_reponsive = json_encode($data_reponsive);
$loop = 'false';

if ( $related_products ) : ?>
	<?php
	if( count($related_products)> 1){
		$loop = 'true';
	}
	$digitalworld_related_products_title = digitalworld_option('digitalworld_related_products_title','Related Products');
	?>

	<div class="block-related">
		<h3 class="block-title">
			<?php echo esc_html($digitalworld_related_products_title);?>
		</h3>
		<div class="block-content ">
			<ul class="product-items owl-carousel kt-owl-carousel equal-container" data-nav="true" data-margin="28" data-loop="<?php echo esc_attr($loop);?>" data-responsive='<?php echo esc_attr($data_reponsive);?>'>


				<?php foreach ( $related_products as $related_product ) : ?>

					<?php
					$post_object = get_post( $related_product->get_id() );

					setup_postdata( $GLOBALS['post'] =& $post_object );

					wc_get_template_part( 'content', 'product-related' );
					?>

				<?php endforeach; ?>
			</ul>
		</div>
	</div>

<?php endif;

wp_reset_postdata();

