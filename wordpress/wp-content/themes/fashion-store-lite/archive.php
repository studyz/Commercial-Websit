<?php get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">



		</main>
	</div>




<section class="main_content">
		<div class="container">
			<div class="row">

				<div class="col-md-9">
					<div class="content-text">
						
						<div class="blog_item">
							<div class="row">
								<div class="col-md-12">
								<?php
		if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header>

			<?php

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
