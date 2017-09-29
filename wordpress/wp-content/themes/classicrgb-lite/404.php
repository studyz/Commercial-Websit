<?php get_header(); ?>










<section class="main_content">
		<div class="container">
			<div class="row">
			    <div class="col-md-9 col-md-push-3">	
				  	<aside class="sidebar-right1">
						<div class="content-text">	
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'classicrgb-lite' ); ?></h1>
					<h1 class="page-title"><?php esc_html_e( '404', 'classicrgb-lite' ); ?></h1>
				</header>
		

						</div>	
					</aside>
				</div>

				<div class="col-md-3 col-md-pull-9"><aside class="sidebar-left"><?php dynamic_sidebar( 'sidebar-1' ); ?></aside></div>
				
			</div>
		</div>
</section>




<?php
get_footer();
