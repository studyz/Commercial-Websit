<?php get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="main_content">
					<div class="container">
						<div class="row">

							
							<div class="col-md-9">
								<div class="content-text">
									
									<div class="blog_item">
										<div class="row">
											<div class="col-md-12">
							
								<header class="page-header">
									<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'fashion-store-lite' ); ?></h1>
									<h1 class="page-title"><?php esc_html_e( '404', 'fashion-store-lite' ); ?></h1>
								</header>


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