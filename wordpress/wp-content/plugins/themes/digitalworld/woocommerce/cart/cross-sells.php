<?php
/**
 * Cross-sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cross-sells.php.
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
    exit;
}

global $product, $woocommerce_loop;

if ( ! $crosssells = WC()->cart->get_cross_sells() ) {
    return;
}

$args = array(
    'post_type'           => 'product',
    'ignore_sticky_posts' => 1,
    'no_found_rows'       => 1,
    'posts_per_page'      => apply_filters( 'woocommerce_cross_sells_total', $posts_per_page ),
    'orderby'             => $orderby,
    'post__in'            => $crosssells,
    'meta_query'          => WC()->query->get_meta_query()
);

$products                    = new WP_Query( $args );
$woocommerce_loop['name']    = 'cross-sells';
$woocommerce_loop['columns'] = apply_filters( 'woocommerce_cross_sells_columns', $columns );


$digitalworld_woo_crosssell_lg_items = digitalworld_option('digitalworld_woo_crosssell_lg_items',4);
$digitalworld_woo_crosssell_md_items = digitalworld_option('digitalworld_woo_crosssell_md_items',4);
$digitalworld_woo_crosssell_sm_items = digitalworld_option('digitalworld_woo_crosssell_sm_items',3);
$digitalworld_woo_crosssell_xs_items = digitalworld_option('digitalworld_woo_crosssell_xs_items',2);
$digitalworld_woo_crosssell_ts_items = digitalworld_option('digitalworld_woo_crosssell_ts_items',1);
$digitalworld_cross_sells_products_title = digitalworld_option('digitalworld_cross_sells_products_title','You may be interested in');

$data_reponsive = array(
    '0'=>array(
        'items'=>$digitalworld_woo_crosssell_ts_items
    ),
    '480'=>array(
        'items'=>$digitalworld_woo_crosssell_xs_items
    ),
    '768'=>array(
        'items'=>$digitalworld_woo_crosssell_sm_items
    ),
    '992'=>array(
        'items'=>$digitalworld_woo_crosssell_md_items
    ),
    '1200'=>array(
        'items'=>$digitalworld_woo_crosssell_lg_items
    ),
);
$data_reponsive = json_encode($data_reponsive);
$loop = 'false';

if ( $products->have_posts() ) :
    if( $products->post_count > 1){
        $loop = 'true';
    }
    ?>
    <div class="cross-sells block-related">
        <?php if( $digitalworld_cross_sells_products_title ) :?>
            <h3 class="block-title">
                <?php echo esc_html( $digitalworld_cross_sells_products_title ); ?>
            </h3>
        <?php endif;?>
        <ul class="product-items owl-carousel kt-owl-carousel equal-container" data-nav="true" data-margin="28" data-loop="<?php echo esc_attr($loop);?>" data-responsive='<?php echo esc_attr($data_reponsive);?>'>
            <?php while ( $products->have_posts() ) : $products->the_post(); ?>
                <?php wc_get_template_part( 'content', 'product-cross-sells' ); ?>
            <?php endwhile; // end of the loop. ?>
    </div>

<?php endif;

wp_reset_query();
