<?php get_header(); ?>




	<section class="main_content">
		<div class="container">
			<div class="row">

				<div class="col-md-9">
					<div class="content-text">
						
						<div class="blog_item">
							<div class="row">
								<div class="col-md-12">
							<?php
			if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) : ?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>

			<?php
			endif;


			while ( have_posts() ) : the_post();






				get_template_part( 'template-parts/content', get_post_format() );

			endwhile;

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<aside class="sidebar-right">
					<?php dynamic_sidebar( 'sidebar-1' ); ?>
					</aside>
				</div>
			</div>
		</div>
	</section>
















<?php

get_footer();
