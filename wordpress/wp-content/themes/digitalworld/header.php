<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package Wordpress
 * @subpackage digitalworld
 * @since digitalworld 1.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<?php get_template_part('template_parts/popup','content');?>
	<div id="box-mobile-menu" class="box-mobile-menu full-height">
		<div class="box-inner">
			<a href="#" class="close-menu"><span class="icon fa fa-times"></span></a>
		</div>
	</div>
	<?php digitalworld_serch_from_mobile();?>
    <?php digitalworld_get_header()?>
	<div class="wapper">
