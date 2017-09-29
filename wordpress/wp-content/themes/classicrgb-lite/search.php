<?php get_header(); ?>










<section class="main_content">
		<div class="container">
			<div class="row">
			    <div class="col-md-9 col-md-push-3">	
				  	<aside class="sidebar-right1">
						<div class="content-text">	
		<?php
		if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'classicrgb-lite' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header>

			<?php

			while ( have_posts() ) : the_post();






				get_template_part( 'template-parts/content', 'search' );

			endwhile;

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>
		

						</div>	
					</aside>
				</div>

				<div class="col-md-3 col-md-pull-9"><aside class="sidebar-left"><?php dynamic_sidebar( 'sidebar-1' ); ?></aside></div>
				
			</div>
		</div>
</section>












<?php
get_footer();
