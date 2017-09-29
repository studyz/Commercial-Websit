<?php get_header();?>
<?php
/*Shop layout*/
$digitalworld_woo_shop_layout = digitalworld_option('digitalworld_woo_shop_layout','left');

if( is_product() ){
	$digitalworld_woo_shop_layout = digitalworld_option('digitalworld_woo_single_layout','left');
}

/*Main container class*/
$main_container_class = array();
$main_container_class[] = 'main-container shop-page';
if( $digitalworld_woo_shop_layout == 'full'){
	$main_container_class[] = 'no-sidebar';
}else{
	$main_container_class[] = $digitalworld_woo_shop_layout.'-slidebar';
}

/*Setting single product*/

$main_content_class = array();
$main_content_class[] = 'main-content';
if( $digitalworld_woo_shop_layout == 'full' ){
	$main_content_class[] ='col-sm-12';
}else{
	$main_content_class[] = 'col-md-9 col-sm-8';
}

$slidebar_class = array();
$slidebar_class[] = 'sidebar col-sidebar';
if( $digitalworld_woo_shop_layout != 'full'){
	$slidebar_class[] = 'col-md-3 col-sm-4';
}

?>
<div class="<?php echo esc_attr( implode(' ', $main_container_class) );?>">
	<div class="columns container">
		<?php
		    /**
		     * woocommerce_before_main_content hook
		     *
		     * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		     * @hooked woocommerce_breadcrumb - 20
		     */
		    do_action( 'woocommerce_before_main_content' );
		?>
		<div class="row">
			<div class="<?php echo esc_attr( implode(' ', $main_content_class) );?>">
				<?php
				/**
			     * digitalworld_woocommerce_before_loop_start hook
			     *
			     * @hooked digitalworld_shop_top_control - 10
			     */
			    do_action( 'digitalworld_woocommerce_before_loop_start' );
				?>
				<?php woocommerce_content(); ?>
			</div>
			<?php if( $digitalworld_woo_shop_layout != "full" ):?>
			<div class="<?php echo esc_attr( implode(' ', $slidebar_class) );?>">
				<?php get_sidebar('shop');?>
			</div>
			<?php endif;?>
		</div>
		<?php
		    /**
		     * woocommerce_before_main_content hook
		     *
		     * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		     * @hooked woocommerce_breadcrumb - 20
		     */
		    do_action( 'woocommerce_after_main_content' );
		?>
	</div>
</div>
<?php get_footer();?>