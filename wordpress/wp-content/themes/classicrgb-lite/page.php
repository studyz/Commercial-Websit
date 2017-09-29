<?php get_header(); ?>


<section class="main_content">
		<div class="container">
			<div class="row">
			    <div class="col-md-9 col-md-push-3">	
				  	<aside class="sidebar-right1">
						<div class="content-text">	
						<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'page' );


				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile;
			?>
		

						</div>	
					</aside>
				</div>

				<div class="col-md-3 col-md-pull-9"><aside class="sidebar-left"><?php dynamic_sidebar( 'sidebar-1' ); ?></aside></div>
				
			</div>
		</div>
</section>






<?php
get_footer();
