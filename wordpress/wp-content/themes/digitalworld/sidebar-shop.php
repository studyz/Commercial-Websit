<?php
/**
 * The sidebar containing the main widget area
 *
 */
?>
<?php
$digitalworld_woo_shop_used_sidebar = digitalworld_option( 'digitalworld_woo_shop_used_sidebar', 'shop-widget-area' );
if( is_product() ){
	$digitalworld_woo_shop_used_sidebar = digitalworld_option('digitalworld_woo_single_used_sidebar','shop-widget-area');
}

?>

<?php if ( is_active_sidebar( $digitalworld_woo_shop_used_sidebar ) ) : ?>
<div id="widget-area" class="widget-area shop-sidebar">
	<?php dynamic_sidebar( $digitalworld_woo_shop_used_sidebar ); ?>
</div><!-- .widget-area -->
<?php endif; ?>
