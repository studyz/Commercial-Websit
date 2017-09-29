<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">



<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">

	<header class="top_header">
		<div class="container">
			<div class="col-md-3">
				<div class="row">


					<div class="site-branding">
						<?php
						if ( is_front_page() && is_home() ) : ?>
							<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<?php else : ?>
							<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<?php
						endif;

						$description = get_bloginfo( 'description', 'display' );
						if ( $description || is_customize_preview() ) : ?>
							<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
						<?php
						endif; ?>
					</div>
				</div>
			</div>
			<div class="col-md-9">
					<div class="row">
						<aside class="sidebar-header3">
								<?php if (class_exists('woocommerce')) :?>
									<?php global $woocommerce; ?>
								<span class="dashicons dashicons-cart"></span> <a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php esc_url('View your shopping cart', 'classicrgb-lite'); ?>"> <?php echo $woocommerce->cart->get_cart_total(); ?></a>
								<?php endif; ?>
						</aside>
					</div>

					<div class="row">
						<aside class="sidebar-header3">
						<?php dynamic_sidebar( 'sidebar-4' ); ?>
						</aside>
					</div>
			</div>

		</div>

	</header>
<div class="top-menu">
			<div class="container">
				<div class="col-md-12">
					<div class="row">
						<?php wp_nav_menu( array( 'theme_location' => 'header-menu' ) ); ?>
					</div>
				</div>
			</div>
</div>

<div id="content" class="site-content">
